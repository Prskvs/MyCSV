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
        catch(Exception $e) {
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
        catch(Exception $e) {
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

    public function getData() {
        try {
            return array_merge($this->_headers, $this->_rows);
        }
        catch(Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function getHeaders() {
        return $this->_headers;
    }

    public function getRows() {
        return $this->_rows;
    }

    public function getRow($index) {
        if (!empty($index) && array_key_exists($index, $this->_rows)) {
            return $this->_rows[$index];
        }

        return false;
    }

    public function parseCSV($csv_name, $max_lines = 1000, $separator = ",", $callback = null) {
        if (($handle = @fopen($csv_name, "r")) !== false) {
            $row_index = 0;
            while (($data = fgetcsv($handle, $max_lines, $separator)) !== false) {

                if (is_callable($callback)) {
                    $data = call_user_func_array($callback, [$data, $row_index]);
                }

                if ($row_index === 0)
                    $this->setHeaders($data);
                else
                    $this->addRow($data);

                $row_index++;
            }

            fclose($handle);
            return true;
        }

        return false;
    }

    public function exportCSV($name = null) {
        if (empty($this->_headers) && empty($this->$this->_rows)) {
            return false;
        }

        if (!empty($name)) {
            $this->_name = $name;
        }

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
