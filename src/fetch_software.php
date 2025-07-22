<?php
require 'vendor/autoload.php'; // 引入Composer自动加载文件
require 'lanzouyunapi.php'; // 引入本地的API文件

// 定义你的网盘ID和密码
$data = [
    'data' => 'b0w8ldo0j', // 网盘ID
    'pw' => 'ruoshui', // 网盘密码
    'types' => 'json',
    'auto' => 1,
    'page' => 1
];

function fetchPage($data) {
    // 调用本地的API文件
    return requestApi($data);
}

function requestApi($data) {
    // 模拟GET请求
    $_GET = $data;
    ob_start(); // 开始输出缓冲
    include 'lanzouyunapi.php'; // 包含API文件
    $output = ob_get_clean(); // 获取缓冲区内容
    return json_decode($output, true); // 解码JSON响应
}

function saveData($data, $filePath) {
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$allData = [];
$currentPage = 1;

do {
    $data['page'] = $currentPage;
    $response = fetchPage($data);
    if ($response['code'] == 0) {
        $allData[] = $response['data'];
        $currentPage++;
    } else {
        break;
    }
} while ($response['data']['have_page']);

$finalData = array_merge([], ...$allData);
saveData($finalData, __DIR__ . '/../data/software_data.json');
echo "数据已保存到 data/software_data.json\n";