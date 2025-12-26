<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
 public function index(Request $request)
    {
        $company_id = session('company_id');

        // Use the getRecord method just like Payroll
        $data['getRecord'] = Payment::getRecord();

        // Add branches data like in Payroll controller
        $data['branches'] = DB::table('branches')
            ->where('company_id', $company_id)
            ->select('id', 'name', 'is_main')
            ->orderBy('name')
            ->get();

        return view('backend.payrolls.Salary Payment.salary-payment', $data);
    }

    public function create()
    {
        $company_id = session('company_id');
        $branch_id = session('branch_id');

        // EXACT SAME LOGIC AS PAYROLL CONTROLLER
        if (empty($branch_id)) {
            $data['getEmployees'] = User::where('company_id', $company_id)->get();
        } else {
            // Check if the current branch is the main branch
            $currentBranch = DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            // If it's the main branch (is_main == 1), show all employees for the company
            if ($currentBranch && $currentBranch->is_main == 1) {
                $data['getEmployees'] = User::where('company_id', $company_id)->get();
            } else {
                // Otherwise, filter by the specific branch_id
                $data['getEmployees'] = User::where('branch_id', $branch_id)->get();
            }
        }

        // Add branches data for the filter dropdown
        $data['branches'] = DB::table('branches')
            ->where('company_id', $company_id)
            ->select('id', 'name', 'is_main')
            ->orderBy('name')
            ->get();

        $data['getPayrolls'] = Payroll::get();

        return view('backend.payrolls.Salary Payment.create', $data);
    }

    /**
     * Get payrolls for AJAX request (used in payment form)
     * FIXED: Now uses proper branch filtering logic
     */
    public function getPayrolls(Request $request)
    {
        $company_id = session('company_id');
        $branch_id = $request->branch_id;
        $month = $request->month;
        $year = $request->year;

        // Start with company and date filters
        $query = Payroll::select('payrolls.*', 'users.name')
            ->join('users', 'users.id', '=', 'payrolls.employee_id')
            ->where('users.company_id', $company_id)
            ->whereMonth('payrolls.start_date', $month)
            ->whereYear('payrolls.start_date', $year);

        /* ===============================
           🔍 Branch Logic (SAME AS PAYROLL)
        ================================ */

        // If a specific branch is selected in the dropdown filter
        if ($branch_id) {
            $query->where('users.branch_id', $branch_id);
        } else {
            // Use session branch logic
            $sessionBranchId = session('branch_id');

            if (!empty($sessionBranchId)) {
                $currentBranch = DB::table('branches')
                    ->where('id', $sessionBranchId)
                    ->select('is_main')
                    ->first();

                // If NOT main branch, filter by branch
                if ($currentBranch && $currentBranch->is_main == 1) {
                    // Main branch → show all company payrolls (no additional filter)
                } else {
                    // Normal branch → only its payrolls
                    $query->where('users.branch_id', $sessionBranchId);
                }
            }
            // If no session branch → show all company payrolls
        }

        $payrolls = $query->get();

        $result = [];
        foreach ($payrolls as $payroll) {
            $totalPaid = Payment::where('payroll_id', $payroll->id)->sum('paid_amount');

            $payrollTypeLabel = match($payroll->payroll_type) {
                'daily' => __('h_payroll.daily'),
                'weekly' => __('h_payroll.weekly'),
                'monthly' => __('h_payroll.monthly'),
                default => $payroll->payroll_type
            };

            $result[] = [
                'id' => $payroll->id,
                'employee_id' => $payroll->employee_id,
                'employee_name' => $payroll->name ?? 'N/A',
                'basic_salary' => $payroll->basic_salary ?? 0,
                'net_pay' => $payroll->net_pay ?? 0,
                'total_paid' => $totalPaid,
                'payroll_type_label' => $payrollTypeLabel,
            ];
        }

        return response()->json([
            'success' => true,
            'payrolls' => $result
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'selected_month' => 'required',
        'selected_year' => 'required',
        'payments' => 'array',
        'payments.*.payroll_id' => 'required|exists:payrolls,id',
        'payments.*.employee_id' => 'required|exists:users,id',
        'payments.*.amount' => 'required|numeric|min:0',
        'payments.*.payment_date' => 'required|date',
    ]);

    $company_id = session('company_id');
    $branch_id = $request->selected_branch ?: session('branch_id');

    $successCount = 0;
    $errors = [];
    $skippedCount = 0;

    DB::beginTransaction();
    try {
        // RULE 1: If no specific payments selected, pay all payrolls in full
        if (!$request->has('payments') || empty($request->payments)) {
            $payrolls = $this->getPayrollsForProcessing(
                $company_id,
                $request->selected_month,
                $request->selected_year,
                $request->selected_branch
            );

            foreach ($payrolls as $payroll) {
                $result = $this->processPayment($payroll, null, $company_id, $branch_id);

                if ($result['skipped']) {
                    $skippedCount++;
                } elseif ($result['success']) {
                    $successCount++;
                } elseif ($result['error']) {
                    $errors[] = $result['error'];
                }
            }
        } else {
            // RULE 2: Process selected payments (partial or full)
            $selectedPayrollIds = array_column($request->payments, 'payroll_id');

            // Process explicitly selected payrolls
            foreach ($request->payments as $paymentData) {
                $payroll = Payroll::join('users', 'users.id', '=', 'payrolls.employee_id')
                    ->where('payrolls.id', $paymentData['payroll_id'])
                    ->where('users.company_id', $company_id)
                    ->select('payrolls.*')
                    ->first();

                if (!$payroll) {
                    $errors[] = __('h_payments.payroll_not_found');
                    continue;
                }

                $result = $this->processPayment($payroll, $paymentData, $company_id, $branch_id);

                if ($result['skipped']) {
                    $skippedCount++;
                } elseif ($result['success']) {
                    $successCount++;
                } elseif ($result['error']) {
                    $errors[] = $result['error'];
                }
            }

            // RULE 2 (continued): Pay remaining unselected payrolls in full
            $unselectedPayrolls = $this->getPayrollsForProcessing(
                $company_id,
                $request->selected_month,
                $request->selected_year,
                $request->selected_branch,
                $selectedPayrollIds
            );

            foreach ($unselectedPayrolls as $payroll) {
                $paymentData = ['payment_date' => $request->payments[0]['payment_date'] ?? now()->format('Y-m-d')];
                $result = $this->processPayment($payroll, $paymentData, $company_id, $branch_id, true);

                if ($result['skipped']) {
                    $skippedCount++;
                } elseif ($result['success']) {
                    $successCount++;
                } elseif ($result['error']) {
                    $errors[] = $result['error'];
                }
            }
        }

        if ($successCount > 0) {
            DB::commit();

            $message = __('h_payments.payments_saved_successfully') . ' (' . $successCount . ')';
            if ($skippedCount > 0) {
                $message .= "\n" . __('h_payments.skipped_fully_paid') . ': ' . $skippedCount;
            }
            if (!empty($errors)) {
                $message .= "\n\n" . __('h_payments.some_errors_occurred') . ":\n• " . implode("\n• ", $errors);
            }

            return redirect('admin/salary-payment')->with('success', $message);
        } else {
            DB::rollBack();
            $errorMessage = __('h_payments.no_payments_processed');
            if ($skippedCount > 0) {
                $errorMessage .= "\n" . __('h_payments.all_payrolls_fully_paid');
            }
            if (!empty($errors)) {
                $errorMessage .= "\n" . implode("\n", $errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }

    } catch (\Exception $e) {
        DB::rollBack();

        $errorMsg = __('h_payments.error_saving_payments') . ': ' . $e->getMessage();

        return redirect()->back()
            ->withInput()
            ->with('error', $errorMsg);
    }
}

    private function getPayrollsForProcessing($company_id, $month, $year, $selected_branch = null, $excludeIds = [])
    {
        $query = Payroll::select('payrolls.*')
            ->join('users', 'users.id', '=', 'payrolls.employee_id')
            ->where('users.company_id', $company_id)
            ->whereMonth('payrolls.start_date', $month)
            ->whereYear('payrolls.start_date', $year);

        if (!empty($excludeIds)) {
            $query->whereNotIn('payrolls.id', $excludeIds);
        }

        /* ===============================
           🔍 Branch Logic (SAME AS PAYROLL)
        ================================ */

        if ($selected_branch) {
            // Manual branch selection from dropdown
            $query->where('users.branch_id', $selected_branch);
        } else {
            // Use session branch logic
            $sessionBranchId = session('branch_id');

            if (!empty($sessionBranchId)) {
                $currentBranch = DB::table('branches')
                    ->where('id', $sessionBranchId)
                    ->select('is_main')
                    ->first();

                if ($currentBranch && $currentBranch->is_main == 1) {
                    // Main branch → show all company payrolls
                } else {
                    // Normal branch → only its payrolls
                    $query->where('users.branch_id', $sessionBranchId);
                }
            }
        }

        return $query->get();
    }

    /**
     * Helper: Process individual payment
     */
    private function processPayment($payroll, $paymentData = null, $company_id, $branch_id, $fullPayment = false)
    {
        $currentTotalPaid = Payment::where('payroll_id', $payroll->id)->sum('paid_amount');
        $remainingAmount = $payroll->net_pay - $currentTotalPaid;

        // RULE 3: Skip if already fully paid
        if ($remainingAmount <= 0) {
            return ['skipped' => true, 'success' => false, 'error' => null];
        }

        // Determine payment amount
        if ($fullPayment || !$paymentData || !isset($paymentData['amount'])) {
            $paidAmount = $remainingAmount;
        } else {
            $paidAmount = $paymentData['amount'];

            // Validate amount doesn't exceed remaining
            if ($paidAmount > $remainingAmount) {
                return [
                    'skipped' => false,
                    'success' => false,
                    'error' => __('h_payments.amount_exceeds_remaining') . ' - ' . ($payroll->employee->name ?? 'N/A')
                ];
            }
        }

        $payment = new Payment();
        $payment->payroll_id = $payroll->id;
        $payment->employee_id = $payroll->employee_id;
        $payment->total_amount = $payroll->net_pay;
        $payment->paid_amount = $paidAmount;
        $payment->remaining_amount = $remainingAmount - $paidAmount;
        $payment->payment_date = $paymentData['payment_date'] ?? now()->format('Y-m-d');
        $payment->company_id = $company_id;
        $payment->branch_id = $branch_id;
        $payment->save();

        return ['skipped' => false, 'success' => true, 'error' => null];
    }


    public function delete($id)
    {
        $payment = Payment::where('id', $id)
            ->where('company_id', session('company_id'))
            ->first();

        if ($payment) {
            $payment->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        Payment::whereIn('id', $ids)
            ->where('company_id', session('company_id'))
            ->delete();

        return response()->json(['success' => true]);
    }
}
