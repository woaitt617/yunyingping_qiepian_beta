<?php
namespace app\system\model;

use think\Model;
use app\system\model\SystemHook as HookModel;

/**
 * 钩子插件索引模型
 * @package app\system\model
 */
class SystemHookPlugins extends Model
{

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 索引入库
     * @param array $hooks 钩子
     * @param string $plugins 插件名称
     * @return bool
     */
    public static function storage($hooks = [], $plugins = '')
    {
        if (!empty($hooks) && is_array($hooks)) {
            $hookModel = new HookModel;
            // 添加钩子
            foreach ($hooks as $k => $v) {
                if (is_numeric($k)) {
                    $k = $v;
                }
                if (!$hookModel->storage(['name' => $k, 'source' => 'plugins.'.$plugins, 'intro' => $v])) {
                    return false;
                }
            }

            $data = [];
            foreach ($hooks as $k => $v) {
                if (is_numeric($k)) {
                    $k = $v;
                }
                // 清除重复数据
                if (self::where(['hook' => $k, 'plugins' => $plugins])->find()) {
                    continue;
                }
                $data[] = [
                    'hook'      => $k,
                    'plugins'   => $plugins,
                ];
            }
            
            if (empty($data)) {
                return true;
            }

            return self::insertAll($data);
        }
        return false;
    }

    /**
     * 删除插件钩子索引
     * @param string $plugins 插件名称
     * @return bool
     */
    public static function del($plugins = '')
    {
        if (!empty($plugins)) {
            // 删除插件钩子
            if (!HookModel::delHook('plugins.'.$plugins)) {
                return false;
            }
            // 删除索引
            if (self::where('plugins', $plugins)->delete() === false) {
                return false;
            }
        }
        return true;
    }
}
