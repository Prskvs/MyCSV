<?php

/**
 * Class MyCSV
 */
class MyCSV {
    protected $_csv;
    protected $_name;
    protected $_headers;
    protected $_rows;
    protected $_separator;

    public function __construct($name = 'mycsv.csv', $separator = ',') {
        $this->_name = $name;
        $this->_headers = array();
        $this->_rows = array();
        $this->_separator = $separator;
    }

    public function setHeaders($columns) {
        if (!is_array($columns)) return false;

        try {
            $this->_headers = $columns;
            return true;
        }
        catch( Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function addRow($columns, $index = null) {
        if (!is_array($columns)) return false;

        try {
            if (!empty($index))
                $this->_rows[$index] = $columns;
            else
                $this->_rows[] = $columns;

            end($this->_rows);
            return key($this->_rows);
        }
        catch( Exception $e) {
            var_dump($e->getMessage());
            return false;
        }

    }

    public function removeRow($index) {
        try {
            unset($this->_rows[$index]);
            return true;
        }
        catch( Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function exportCSV() {
        if (empty($this->_headers) && empty($this->$this->_rows)) return false;

        try {
            $this->_csv = fopen($this->_name, 'w');
            fputcsv($this->_csv, $this->_headers, $this->_separator);
            foreach ($this->_rows as $row) {
                fputcsv($this->_csv, $row, $this->_separator);
            }
            fclose($this->_csv);
            return true;
        }
        catch(Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function dump($asHTML = false) {
        if ($asHTML) {
            $index = 0;
            echo '<pre>';
            echo '<table border="1">';
            echo '<tr><th>' . $index++ . '</th><th>' . implode('</th><th>', $this->_headers) . '</th></tr>';
            foreach ($this->_rows as $key => $row) {
                echo '<tr><td>' . $index++ . '</td><td>' . implode('</td><td>', $row) . '</td></tr>';
            }
            echo '</table>';
            echo '</pre>';
        }
        else {
            var_dump("Headers: " . implode($this->_separator, $this->_headers));
            var_dump('Total Rows : ' . count($this->_rows));
            foreach ($this->_rows as $key => $row) {
                var_dump("Row $key: " . implode($this->_separator, $row));
            }
        }
    }
}
?>
