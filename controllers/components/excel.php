<?php

App::import('Vendor', 'PHPExcel', array('file' => 'excel' . DS . 'PHPExcel.php'));
App::import('Vendor', 'PHPExcelWriter', array('file' => 'excel' . DS . 'PHPExcel' . DS . 'Writer' . DS . 'Excel2007.php'));

class ExcelComponent extends Object {
    protected $activeSheetIndex = 0;
    protected $data = array();
    protected $saveDirectory;
    protected $settings = array();
    protected $sheet = null;
    protected $writer;
    protected $xls;

    public function initialize(&$controller, $settings = array()) {
        $this->settings = array_merge($this->settings, $settings);
        // $this->saveDirectory = Configure::read('Documents.storage.path');
    }

    public function create($title) {
        $this->xls = new PHPExcel();
        $this->xls->getProperties()->setCreator('ATLAS');
        $this->xls->getProperties()->setTitle($title);
        $this->sheet = $this->xls->getActiveSheet();
        $this->setTitleRow($title);
        $this->writer = new PHPExcel_Writer_Excel2007($this->xls);
        $this->setExcelDefaults();
    }

    public function setWorksheetTitle($title) {
        $this->sheet->setTitle($title);
    }

    public function addWorksheet($title = 'Worksheet') {
        $this->xls->createSheet();
        $this->activeSheetIndex += 1;
        $this->xls->setActiveSheetIndex($this->activeSheetIndex);
        $this->sheet = $this->xls->getActiveSheet();
        $this->setWorksheetTitle($title);
    }

    public function setTitleRow($title) {
        $this->sheet->SetCellValue('A1', $title);
        $this->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $this->sheet->mergeCells('A1:F1');
    }

    public function setHeaders($headers = array()) {
        $i=0;
        foreach ($headers as $header) {
            $columnName = Inflector::humanize($header);
            $this->sheet->setCellValueByColumnAndRow($i++, 3, $columnName);
        }
        $this->sheet->getStyle('A3')->getFont()->setBold(true)->setSize(16);
        $this->sheet->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->sheet->getStyle('A3')->getFill()->getStartColor()->setRGB('969696');
        $this->sheet->duplicateStyle( $this->sheet->getStyle('A3'), 'B3:'.$this->sheet->getHighestColumn().'3');
    }

    public function setData($data) {
        $i=4;
        foreach ($data as $row) {
            $j=0;
            foreach ($row as $field => $value) {
                if ($field === 'ssn') {
                    $this->sheet->setCellValueExplicitByColumnAndRow($j++, $i, $value, PHPExcel_Cell_DataType::TYPE_STRING);
                } else {
                    $this->sheet->setCellValueByColumnAndRow($j++, $i, $value);
                }
            }
            $i++;
        }
    }

    public function save($filename) {
        for ($col = 'A'; $col != 'J'; $col++) {
            $this->sheet->getColumnDimension('A')->setAutoSize(true);
            $this->sheet->getColumnDimension('B')->setAutoSize(true);
            $this->sheet->getColumnDimension('C')->setAutoSize(true);
            $this->sheet->getColumnDimension('D')->setAutoSize(true);
            $this->sheet->getColumnDimension('E')->setAutoSize(true);
            $this->sheet->getColumnDimension('F')->setAutoSize(true);
            $this->sheet->getColumnDimension('G')->setAutoSize(true);
            $this->sheet->getColumnDimension('H')->setAutoSize(true);
            $this->sheet->getColumnDimension('I')->setAutoSize(true);
        }

        if (!is_dir($this->saveDirectory)) {
            mkdir($this->saveDirectory);
        }

        if (file_exists("$filename.xlsx")) {
            unlink("$filename.xlsx");
        }

        $this->writer->save("$filename.xlsx");
    }

    public function saveDirectory($dir) {
        $this->saveDirectory = './';
    }

    private function setExcelDefaults() {
        $this->sheet->getDefaultStyle()->getFont()->setName('Verdana');
    }
}

