<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Funcao {

  public static function get_array_column($input, $column_key, $index_key = null) {
    if (!function_exists('array_column')) {
      if ($index_key !== null) {
        // Collect the keys
        $keys = array();
        $i = 0; // Counter for numerical keys when key does not exist

        foreach ($input as $row) {
          if (array_key_exists($index_key, $row)) {
            // Update counter for numerical keys
            if (is_numeric($row[$index_key]) || is_bool($row[$index_key])) {
              $i = max($i, (int) $row[$index_key] + 1);
            }

            // Get the key from a single column of the array
            $keys[] = $row[$index_key];
          } else {
            // The key does not exist, use numerical indexing
            $keys[] = $i++;
          }
        }
      }

      if ($column_key !== null) {
        // Collect the values
        $values = array();
        $i = 0; // Counter for removing keys

        foreach ($input as $row) {
          if (array_key_exists($column_key, $row)) {
            // Get the values from a single column of the input array
            $values[] = $row[$column_key];
            $i++;
          } elseif (isset($keys)) {
            // Values does not exist, also drop the key for it
            array_splice($keys, $i, 1);
          }
        }
      } else {
        // Get the full arrays
        $values = array_values($input);
      }

      if ($index_key !== null) {
        return array_combine($keys, $values);
      }

      return $values;
    } else { //Se existe a função
      return array_column($input, $column_key, $index_key = null);
    }
  }

  /**
   * Método para converter o valor do registro pelo tipo da propriedade
   * @param mixed $key Propriedae da classe
   * @param mixed $value O campo do registro do banco de dados
   * @return null|string|int|float|boolean Valor convertido
   */
  public static function getValueByType($key, $value) {
    if (is_null($key)) {
      return null;
    }
    if (is_string($key)) {
      return $value;
    }
    if (is_int($key)) {
      return (int) $value;
    }
    if (is_bool($key)) {
      return (boolean) $value;
    }
    if (is_float($key)) {
      return (float) $value;
    }
  }

  public $array = array();

  public function findkeyval($arr, $key) {
    if (isset($arr[$key])) {
      return $arr[$key];
    } else {
      foreach ($arr as $a) {
        if (is_array($a)) {
          $val = findkeyval($a, $key);
          if ($val) {
            return $val;
          }
        }
      }
    }
  }

  public function coluna($input, $column_key) {
    
    

    $values = array();
    $i = 0; // Counter for removing keys
    if (is_array($input)) {
      foreach ($input as $row) {
        //Verifica se existe
        if (is_array($row)) {
          if (array_key_exists($column_key, $row)) {
            $this->array[] = $row[$column_key];
          } else {
            if (is_array($row)) {
              $this->coluna($row, $column_key);
            }
          }
        }
      }
    }
    return $this->array;
  }

}

?>