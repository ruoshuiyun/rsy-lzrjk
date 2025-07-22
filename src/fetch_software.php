<?php
require 'vendor/autoload.php'; // 引入Composer自动加载文件
require 'simple_html_dom.php'; // HTML解析
include "lanzouyunapiconfig.php"; // 配置文件

// 设置默认参数
$data = [
    'data' => 'b0w8ldo0j', // 替换为你的网盘ID
    'pw' => 'ruoshui', // 替换为你的网盘密码
    'types' => 'json',
    'auto' => 1,
    'page' => 1
];

// 检查是否有命令行参数覆盖默认值
if (isset($argv[1])) {
    $data['data'] = $argv[1];
}
if (isset($argv[2])) {
    $data['pw'] = $argv[2];
}
if (isset($argv[3])) {
    $data['page'] = $argv[3];
}

function fetchPage($apiUrl, $data) {
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $apiUrl, ['query' => $data]);
    return json_decode($response->getBody(), true);
}

function saveData($data, $filePath) {
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$allData = [];
$currentPage = 1;

do {
    $data['page'] = $currentPage;
    $response = fetchPage($apiUrl, $data);
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
