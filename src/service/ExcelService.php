<?php

// +----------------------------------------------------------------------
// | ThinkLibrary 6.0 for ThinkPhP 6.0
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 [ https://www.dtapp.net ]
// +----------------------------------------------------------------------
// | 官方网站: https://gitee.com/liguangchun/ThinkLibrary
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 仓库地址 ：https://gitee.com/liguangchun/ThinkLibrary
// | github 仓库地址 ：https://github.com/GC0202/ThinkLibrary
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

namespace DtApp\ThinkLibrary\service;

use DtApp\ThinkLibrary\Service;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use think\Exception;

/**
 * 表格服务
 * Class ExcelService
 * @package DtApp\ThinkLibrary\service
 */
class ExcelService extends Service
{
    /**
     * 头部
     * @var array
     */
    private $head = [];

    /**
     * 设置头部
     * [
     *    [
     *       [
     *           'index' => 1,
     *           'value' => '标题'
     *       ],
     *       [
     *           'index' => 2,
     *           'value' => '名称'
     *       ]
     *    ],
     *    [
     *       [
     *          'index' => 1,
     *          'value' => '标题2'
     *       ],
     *       [
     *          'index' => 2,
     *          'value' => '名称2'
     *       ]
     *    ]
     * ];
     * @param array $head
     * @return ExcelService
     */
    public function setHead(array $head = []): ExcelService
    {
        $this->head = $head;
        return $this;
    }

    /**
     * 头部长度
     * @var array
     */
    private $head_length = 0;

    /**
     * 设置头部长度
     * @param int $length
     * @return ExcelService
     */
    public function setHeadLength(int $length = 0): ExcelService
    {
        $this->head_length = $length;
        return $this;
    }

    /**
     * 内容
     * @var array
     */
    private $content = [];

    /**
     * 设置内容
     * [
     *    [
     *       [
     *           'index' => 1,
     *           'value' => '标题'
     *       ],
     *       [
     *           'index' => 2,
     *           'value' => '名称'
     *       ]
     *    ],
     *    [
     *       [
     *          'index' => 1,
     *          'value' => '标题2'
     *       ],
     *       [
     *          'index' => 2,
     *          'value' => '名称2'
     *       ]
     *    ]
     * ];
     * @param array $content
     * @return ExcelService
     */
    public function setContent(array $content = []): ExcelService
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 文件名
     * @var string
     */
    private $file_name = '';

    /**
     * 设置文件名（不需要后缀名）
     * @param string $file_name
     * @return $this
     */
    public function setFileName(string $file_name = ''): self
    {
        $this->file_name = $file_name;
        return $this;
    }

    /**
     * 生成表格文件
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws Exception
     */
    public function generate(): string
    {
        // 生成表格
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if (empty($this->head)) {
            throw new Exception('头部内容未设置!');
        }
        if (empty($this->head_length)) {
            throw new Exception('头部长度未设置!');
        }
        if (empty($this->file_name)) {
            throw new Exception('文件保存路径未设置!');
        }
        //设置工作表标题名称
        //设置单元格内容
        foreach ($this->head as $key => $value) {
            foreach ($value as $k => $v) {
                $sheet->setCellValueByColumnAndRow($v['index'], $key + 1, $v['value']);
            }
        }
        foreach ($this->content as $key => $value) {
            foreach ($value as $k => $v) {
                $sheet->setCellValueByColumnAndRow($v['index'], $key + $this->head_length, $v['value']);
            }
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save("{$this->file_name}.xlsx");

        return "{$this->file_name}.xlsx";
    }
}