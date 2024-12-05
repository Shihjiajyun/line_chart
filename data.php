<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>表格示例</title>

    <link rel="stylesheet" href="https://jsuites.net/v5/jsuites.css" type="text/css" />
    <link rel="stylesheet" href="https://jspreadsheet.com/v11/jspreadsheet.css" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons" />
</head>

<body>
    <div id="spreadsheet"></div>
    <button id="saveButton">保存數據</button>
    <script src="https://jspreadsheet.com/v11/jspreadsheet.js"></script>
    <script src="https://jsuites.net/v5/jsuites.js"></script>
    <script>
        // Set your JSS license key (The following key only works for one day)
        jspreadsheet.setLicense(
            'NjZkYWNkODI5ZjQ2NmMwMzBkOGFhNjUyMzNlYTZiNzBmNGM2NWMyMGRjMGRhOWU1MTZiZGNlY2ZlOTNiMTUzYTc2OGM4NTU2OTRjYmYyYTExZWUxZTdlNDI3NWQ4MGI2NWYwNmM2ZDg2YzE0Y2EyYjgyYTE4ZWEyNzBlNmQ3ZTEsZXlKamJHbGxiblJKWkNJNklpSXNJbTVoYldVaU9pSktjM0J5WldGa2MyaGxaWFFpTENKa1lYUmxJam94TnpNek5EYzJNVFU0TENKa2IyMWhhVzRpT2xzaWFuTndjbVZoWkhOb1pXVjBMbU52YlNJc0ltTnZaR1Z6WVc1a1ltOTRMbWx2SWl3aWFuTm9aV3hzTG01bGRDSXNJbU56WWk1aGNIQWlMQ0ozWldJaUxDSnNiMk5oYkdodmMzUWlYU3dpY0d4aGJpSTZJak0wSWl3aWMyTnZjR1VpT2xzaWRqY2lMQ0oyT0NJc0luWTVJaXdpZGpFd0lpd2lkakV4SWl3aVkyaGhjblJ6SWl3aVptOXliWE1pTENKbWIzSnRkV3hoSWl3aWNHRnljMlZ5SWl3aWNtVnVaR1Z5SWl3aVkyOXRiV1Z1ZEhNaUxDSnBiWEJ2Y25SbGNpSXNJbUpoY2lJc0luWmhiR2xrWVhScGIyNXpJaXdpYzJWaGNtTm9JaXdpY0hKcGJuUWlMQ0p6YUdWbGRITWlMQ0pqYkdsbGJuUWlMQ0p6WlhKMlpYSWlMQ0p6YUdGd1pYTWlYU3dpWkdWdGJ5STZkSEoxWlgwPQ=='
        );

        // 創建電子表格
        const spreadsheet = jspreadsheet(document.getElementById('spreadsheet'), {
            tabs: true,
            toolbar: true,
            worksheets: [{
                minDimensions: [6, 6],
            }],
        });

        // 獲取表格數據的函數
        function getTableData() {
            const rows = document.querySelectorAll('#spreadsheet table tbody tr');
            const data = [];
            rows.forEach(row => {
                const rowData = [];
                row.querySelectorAll('td').forEach(cell => {
                    rowData.push(cell.innerText || '');
                });
                data.push(rowData);
            });
            return data;
        }

        // 保存數據事件處理器
        document.getElementById('saveButton').addEventListener('click', async function() {
            try {
                // 使用 getTableData 提取數據
                const data = getTableData();
                console.log('準備發送的數據:', data); // 調試

                // 獲取 URL 中的參數
                const urlParams = new URLSearchParams(window.location.search);
                const id = urlParams.get('id');
                const number = urlParams.get('number');
                const process = urlParams.get('process');

                // 發送數據到後端
                const url = `php/save_data.php?id=${id}&number=${number}&process=${process}`;
                console.log('請求 URL:', url); // 調試

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        data
                    }),
                });

                console.log('伺服器返回:', response); // 調試
                if (!response.ok) {
                    throw new Error(`HTTP 錯誤！狀態碼: ${response.status}`);
                }

                const result = await response.json();
                console.log('伺服器返回結果:', result); // 調試

                if (result.status === "success") {
                    alert(result.message || "數據保存成功！");
                } else {
                    const errorMessage = result.message || "保存失敗，請檢查伺服器日誌！";
                    alert(`保存失敗：${errorMessage}`);
                }

            } catch (error) {
                console.error('保存時發生錯誤:', error);
                alert('保存數據時發生錯誤，請稍後重試！');
            }
        });
    </script>

</body>

</html>