document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('barChart').getContext('2d');

    // Get current language from HTML lang attribute or a data attribute
    var currentLang = document.documentElement.lang || 'en';

    // Localized labels
    var labels = {
        en: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ar: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
        au: ['جنوری', 'فروری', 'مارچ', 'اپریل', 'مئی', 'جون', 'جولائی', 'اگست', 'ستمبر', 'اکتوبر', 'نومبر', 'دسمبر']

    };

    // Localized dataset labels
    var datasetLabels = {
        en: {
            present: 'Present Per Week',
            absent: 'Absent',
            vacations: 'Vacations'
        },
        ar: {
            present: 'الحضور الأسبوعي',
            absent: 'الغياب',
            vacations: 'الإجازات'
        },
         au: {
        present: 'ہفتہ وار حاضری',
        absent: 'غیر حاضر',
        vacations: 'چھٹیاں'
        }
    };

    var chartData = {
        labels: labels[currentLang] || labels.en,
        datasets: [{
            label: datasetLabels[currentLang]?.present || datasetLabels.en.present,
            backgroundColor: 'rgba(75,192,192,5)', // Keep original colors
            borderColor: 'rgba(75,192,192,1)',
            data: chartDataPresent // Use dynamic present data
        }, {
            label: datasetLabels[currentLang]?.absent || datasetLabels.en.absent,
            backgroundColor: 'rgba(255,99,132,5)', // Keep original colors
            borderColor: 'rgba(255,99,132,1)',
            data: chartDataAbsent // Use dynamic absent data
        }, {
            label: datasetLabels[currentLang]?.vacations || datasetLabels.en.vacations,
            backgroundColor: 'rgba(60,141,188,0.9)',
            borderColor: 'rgba(60,141,188,0.8)',
            data: chartDataVacations // Use dynamic vacation data
        }]
    };

    var options = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        // Add RTL support for Arabic
        plugins: {
            legend: {
                rtl: currentLang === 'ar'
            }
        }
    };

    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: options
    });
});
