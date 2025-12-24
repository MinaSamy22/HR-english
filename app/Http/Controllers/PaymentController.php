<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $company_id = session('company_id');
        $branch_id = session('branch_id');

        $query = Payment::with(['employee', 'payroll'])
            ->where('company_id', $company_id);

        if ($branch_id !== null) {
            $currentBranch = DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            if (!$currentBranch || $currentBranch->is_main != 1) {
                $query->where('branch_id', $branch_id);
            }
        }

        if ($request->filled('name')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('filter_branch_id')) {
            $query->where('branch_id', $request->filter_branch_id);
        }

        if ($request->filled('month')) {
            $query->whereMonth('payment_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('payment_date', $request->year);
        }

        $data['getRecord'] = $query->orderBy('payment_date', 'desc')->paginate(15);

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

        $data['branches'] = DB::table('branches')
            ->where('company_id', $company_id)
            ->select('id', 'name', 'is_main')
            ->orderBy('name')
            ->get();

        return view('backend.payrolls.Salary Payment.create', $data);
    }

    public function getPayrolls(Request $request)
    {
        $company_id = session('company_id');
        $branch_id = $request->branch_id;
        $month = $request->month;
        $year = $request->year;

        $query = Payroll::with(['employee'])
            ->where('company_id', $company_id)
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year);

        if ($branch_id) {
            $query->where('branch_id', $branch_id);
        } else {
            $sessionBranchId = session('branch_id');
            if ($sessionBranchId !== null) {
                $currentBranch = DB::table('branches')
                    ->where('id', $sessionBranchId)
                    ->select('is_main')
                    ->first();

                if (!$currentBranch || $currentBranch->is_main != 1) {
                    $query->where('branch_id', $sessionBranchId);
                }
            }
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
                'employee_name' => $payroll->employee->name ?? 'N/A',
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
                // Get all payrolls for the selected month/year
                $query = Payroll::where('company_id', $company_id)
                    ->whereMonth('start_date', $request->selected_month)
                    ->whereYear('start_date', $request->selected_year);

                if ($request->selected_branch) {
                    $query->where('branch_id', $request->selected_branch);
                } else {
                    $sessionBranchId = session('branch_id');
                    if ($sessionBranchId !== null) {
                        $currentBranch = DB::table('branches')
                            ->where('id', $sessionBranchId)
                            ->select('is_main')
                            ->first();

                        if (!$currentBranch || $currentBranch->is_main != 1) {
                            $query->where('branch_id', $sessionBranchId);
                        }
                    }
                }

                $payrolls = $query->get();

                foreach ($payrolls as $payroll) {
                    $currentTotalPaid = Payment::where('payroll_id', $payroll->id)->sum('paid_amount');
                    $remainingAmount = $payroll->net_pay - $currentTotalPaid;

                    // RULE 3: Skip payrolls that are already fully paid
                    if ($remainingAmount <= 0) {
                        $skippedCount++;
                        continue;
                    }

                    // Pay full remaining amount
                    $payment = new Payment();
                    $payment->payroll_id = $payroll->id;
                    $payment->employee_id = $payroll->employee_id;
                    $payment->total_amount = $payroll->net_pay;
                    $payment->paid_amount = $remainingAmount;
                    $payment->remaining_amount = 0;
                    $payment->payment_date = now()->format('Y-m-d');
                    $payment->company_id = $company_id;
                    $payment->branch_id = $branch_id;
                    $payment->save();

                    $successCount++;
                }
            } else {
                // RULE 2: Process selected payments (partial or full)
                $selectedPayrollIds = array_column($request->payments, 'payroll_id');

                // First, process the explicitly selected payrolls
                foreach ($request->payments as $paymentData) {
                    $payroll = Payroll::where('id', $paymentData['payroll_id'])
                        ->where('company_id', $company_id)
                        ->first();

                    if (!$payroll) {
                        $errors[] = __('h_payments.payroll_not_found');
                        continue;
                    }

                    $currentTotalPaid = Payment::where('payroll_id', $payroll->id)->sum('paid_amount');
                    $remainingBeforePayment = $payroll->net_pay - $currentTotalPaid;

                    // RULE 3: Skip if already fully paid
                    if ($remainingBeforePayment <= 0) {
                        $skippedCount++;
                        continue;
                    }

                    if ($paymentData['amount'] > $remainingBeforePayment) {
                        $errors[] = __('h_payments.amount_exceeds_remaining') . ' - ' . ($payroll->employee->name ?? 'N/A');
                        continue;
                    }

                    $remainingAfterPayment = $remainingBeforePayment - $paymentData['amount'];

                    $payment = new Payment();
                    $payment->payroll_id = $paymentData['payroll_id'];
                    $payment->employee_id = $paymentData['employee_id'];
                    $payment->total_amount = $payroll->net_pay;
                    $payment->paid_amount = $paymentData['amount'];
                    $payment->remaining_amount = $remainingAfterPayment;
                    $payment->payment_date = $paymentData['payment_date'];
                    $payment->company_id = $company_id;
                    $payment->branch_id = $branch_id;
                    $payment->save();

                    $successCount++;
                }

                // RULE 2 (continued): Pay remaining unselected payrolls in full
                $query = Payroll::where('company_id', $company_id)
                    ->whereMonth('start_date', $request->selected_month)
                    ->whereYear('start_date', $request->selected_year)
                    ->whereNotIn('id', $selectedPayrollIds);

                if ($request->selected_branch) {
                    $query->where('branch_id', $request->selected_branch);
                } else {
                    $sessionBranchId = session('branch_id');
                    if ($sessionBranchId !== null) {
                        $currentBranch = DB::table('branches')
                            ->where('id', $sessionBranchId)
                            ->select('is_main')
                            ->first();

                        if (!$currentBranch || $currentBranch->is_main != 1) {
                            $query->where('branch_id', $sessionBranchId);
                        }
                    }
                }

                $unselectedPayrolls = $query->get();

                foreach ($unselectedPayrolls as $payroll) {
                    $currentTotalPaid = Payment::where('payroll_id', $payroll->id)->sum('paid_amount');
                    $remainingAmount = $payroll->net_pay - $currentTotalPaid;

                    // RULE 3: Skip payrolls that are already fully paid
                    if ($remainingAmount <= 0) {
                        $skippedCount++;
                        continue;
                    }

                    // Pay full remaining amount
                    $payment = new Payment();
                    $payment->payroll_id = $payroll->id;
                    $payment->employee_id = $payroll->employee_id;
                    $payment->total_amount = $payroll->net_pay;
                    $payment->paid_amount = $remainingAmount;
                    $payment->remaining_amount = 0;
                    $payment->payment_date = $request->payments[0]['payment_date'] ?? now()->format('Y-m-d');
                    $payment->company_id = $company_id;
                    $payment->branch_id = $branch_id;
                    $payment->save();

                    $successCount++;
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
            return redirect()->back()
                ->withInput()
                ->with('error', __('h_payments.error_saving_payments') . ': ' . $e->getMessage());
        }
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
