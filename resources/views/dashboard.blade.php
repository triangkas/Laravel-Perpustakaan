<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- {{ __("You're logged in!") }} -->
                    <div style="width: 500px">
                        <h5 style="text-align: center">Rekap Peminjaman Buku Per Minggu</h5>
                        <div id="pieChartLoad">Loading ...</div>
                        <canvas id="pieChart"></canvas>
                    <div>
                </div>
            </div>
        </div>
    </div>
    @section('js')
        <script>
            chartGrafik();

            let chartInstance = null;
            async function chartGrafik(pilihan){
                let url = {!! json_encode(route('chart.json')) !!};
                try {
                    let response = await fetch(url);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    let data = await response.json();

                    const labels = data.labels;
                    const datasets = data.datasets;

                    if (chartInstance) {
                        chartInstance.destroy();
                    }

                    const ctx = document.getElementById('pieChart').getContext('2d');
                    chartInstanceProdi = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: datasets.map(dataset => ({
                                label: dataset.label,
                                data: dataset.data,
                                fill: false,
                            }))
                        },
                        options: {
                            responsive: true,
                        }
                    });
                    $('#pieChartLoad').hide();
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }
        </script>
    @endsection
</x-app-layout>
