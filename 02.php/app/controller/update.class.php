<?php

/*
 * 与系统更新操作相关
 */

class Update extends Controller
{
    function __construct()
    {
        /*
         * 避免子类的构造函数覆盖父类的构造函数
         */
        parent::__construct();

        /*
         * 其它自定义操作
         */
        $this->Config = new Config();
    }

    /**
     * 获取当前版本信息
     */
    function GetVersionInfo($requestPayload)
    {
        $data['status'] = 1;
        $data['message'] = '成功';
        $data['data'] = array(
            'current_verson' => VERSION,
            'github' => 'https://github.com/witersen/svnAdminV2.0',
            'gitee' => 'https://gitee.com/witersen/SvnAdminV2.0',
            'author' => 'https://www.witersen.com'
        );
        return $data;
    }

    /**
     * 检测新版本
     */
    function CheckUpdate($requestPayload)
    {
        foreach (UPDATE_SERVER as $key => $value) {
            $versionInfo = curl_request($value);
            if ($versionInfo != null) {
                $versionInfo = json_decode($versionInfo, true);
                $latestVersion = $versionInfo['latestVersion'];
                if ($latestVersion == VERSION) {
                    $data['status'] = 1;
                    $data['message'] = '当前版本为最新版';
                    $data['data'] = null;
                    return $data;
                } else if ($latestVersion > VERSION) {
                    $data['status'] = 1;
                    $data['message'] = '有更新';
                    $data['data'] = array(
                        'latestVersion' => $versionInfo['latestVersion'],
                        'fixedContent' => implode('<br>', $versionInfo['fixedContent']) == '' ? '暂无内容' : implode('<br>', $versionInfo['fixedContent']),
                        'newContent' => implode('<br>', $versionInfo['newContent']) == '' ? '暂无内容' : implode('<br>', $versionInfo['newContent']),
                        'updateType' => $versionInfo['updateType'],
                        'updateStep' => $versionInfo['updateStep']
                    );
                    return $data;
                } else if ($latestVersion < VERSION) {
                    $data['status'] = 0;
                    $data['message'] = '系统版本错误';
                    $data['data'] = null;
                    return $data;
                }
            }
        }
        $data['status'] = 0;
        $data['message'] = '检测更新超时';
        return $data;
    }

    /**
     * 确认更新
     */
    function StartUpdate($requestPayload)
    {
    }
}
