<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>折線圖數據輸入 - 表格區分與順序調整</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.1.1"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .chart-container {
            width: 80%;
            margin: auto;
        }

        .toggle-buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        button {
            margin: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .table-container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            /* 表格超出時自動換行 */
            gap: 10px;
            /* 單元格間距 */
        }

        .table-item {
            flex: 0 0 calc(20% - 10px);
            /* 固定寬度比例，每行最多顯示5個單元格 */
            box-sizing: border-box;
            padding: 5px;
        }

        .table-item input {
            width: 100%;
            /* 讓輸入框填滿單元格 */
            box-sizing: border-box;
            padding: 8px;
            font-size: 16px;
        }

        .table-item label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .q-field {
            background-color: #e0f7fa;
            /* Q 欄位顏色 */
            border: 1px solid #00acc1;
        }

        .c-field {
            background-color: #f3e5f5;
            /* C 欄位顏色 */
            border: 1px solid #8e24aa;
        }

        .spacer {
            flex-basis: 100%;
            /* 空行占滿整行寬度 */
            height: 20px;
            /* 空行高度 */
        }
    </style>
</head>

<body>
    <h1>數據輸入及即時折線圖</h1>
    <div class="chart-container">
        <canvas id="lineChart"></canvas>
    </div>
    <div class="toggle-buttons">
        <button onclick="switchTable('5Q4C')">5Q4C 表格</button>
        <button onclick="switchTable('20Q19C')">20Q19C 表格</button>
    </div>
    <div class="table-container" id="tableContainer"></div>

    <script>
        const ctx = document.getElementById('lineChart').getContext('2d');
        let lineChart;

        function initChart() {
            if (lineChart) lineChart.destroy(); // 如果圖表已存在，銷毀它
            lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: '輸入數據',
                        data: [],
                        borderColor: 'blue',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        annotation: {
                            annotations: {
                                line1: {
                                    type: 'line',
                                    yMin: 3.5,
                                    yMax: 3.5,
                                    borderColor: 'red',
                                    borderWidth: 2,
                                    label: {
                                        enabled: true,
                                        content: '3.5輔助線',
                                        position: 'end',
                                        color: 'red'
                                    }
                                },
                                line2: {
                                    type: 'line',
                                    yMin: 8,
                                    yMax: 8,
                                    borderColor: 'red',
                                    borderWidth: 2,
                                    label: {
                                        enabled: true,
                                        content: '8輔助線',
                                        position: 'end',
                                        color: 'red'
                                    }
                                },
                                redZone: {
                                    type: 'box',
                                    yMin: 2.5,
                                    yMax: 3.5,
                                    backgroundColor: 'rgba(255, 0, 0, 0.7)' // 紅色區塊 (透明度70%)
                                },
                                blueZone: {
                                    type: 'box',
                                    yMin: 7,
                                    yMax: 9,
                                    backgroundColor: 'rgba(0, 0, 255, 0.7)' // 藍色區塊 (透明度70%)
                                },
                                grayZone1: {
                                    type: 'box',
                                    yMin: 2,
                                    yMax: 2.5,
                                    backgroundColor: 'rgba(128, 128, 128, 0.7)' // 灰色區塊 (透明度70%)
                                },
                                grayZone2: {
                                    type: 'box',
                                    yMin: 3.5,
                                    yMax: 4,
                                    backgroundColor: 'rgba(128, 128, 128, 0.7)' // 灰色區塊 (透明度70%)
                                },
                                grayZone3: {
                                    type: 'box',
                                    yMin: 6,
                                    yMax: 7,
                                    backgroundColor: 'rgba(128, 128, 128, 0.7)' // 灰色區塊 (透明度70%)
                                },
                                grayZone4: {
                                    type: 'box',
                                    yMin: 9,
                                    yMax: 12,
                                    backgroundColor: 'rgba(128, 128, 128, 0.7)' // 灰色區塊 (透明度70%)
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: '欄位名稱'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: '數值'
                            },
                            min: 0,
                            max: 12
                        }
                    }
                }
            });
        }

        // 更新折線圖數據
        function updateChart() {
            const qInputs = document.querySelectorAll('.q-field input');
            const cInputs = document.querySelectorAll('.c-field input');

            const labels = [];
            const data = [];

            const maxLength = Math.max(qInputs.length, cInputs.length);
            for (let i = 0; i < maxLength; i++) {
                if (qInputs[i]) {
                    labels.push(qInputs[i].name);
                    data.push(qInputs[i].value ? Number(qInputs[i].value) : 0);
                }
                if (cInputs[i]) {
                    labels.push(cInputs[i].name);
                    data.push(cInputs[i].value ? Number(cInputs[i].value) : 0);
                }
            }

            lineChart.data.labels = labels;
            lineChart.data.datasets[0].data = data;
            lineChart.update();
        }

        // 切換表格
        function switchTable(type) {
            const tableContainer = document.getElementById('tableContainer');
            tableContainer.innerHTML = '';
            let qHeaders = [];
            let cHeaders = [];

            if (type === '5Q4C') {
                qHeaders = ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'];
                cHeaders = ['C1', 'C2', 'C3', 'C4'];
            } else if (type === '20Q19C') {
                qHeaders = Array.from({
                    length: 20
                }, (_, i) => `Q${i + 1}`);
                cHeaders = Array.from({
                    length: 19
                }, (_, i) => `C${i + 1}`);
            }

            qHeaders.forEach(header => {
                const item = document.createElement('div');
                item.className = 'table-item q-field';
                item.innerHTML = `
                    <label for="${header}">${header}</label>
                    <input type="number" id="${header}" name="${header}" value="0" oninput="updateChart()">
                `;
                tableContainer.appendChild(item);
            });

            const spacer = document.createElement('div');
            spacer.className = 'spacer';
            tableContainer.appendChild(spacer);

            cHeaders.forEach(header => {
                const item = document.createElement('div');
                item.className = 'table-item c-field';
                item.innerHTML = `
                    <label for="${header}">${header}</label>
                    <input type="number" id="${header}" name="${header}" value="0" oninput="updateChart()">
                `;
                tableContainer.appendChild(item);
            });

            updateChart();
        }

        initChart();
        switchTable('5Q4C');
    </script>

</body>

</html>