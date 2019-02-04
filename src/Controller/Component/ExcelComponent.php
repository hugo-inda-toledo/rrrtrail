<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Exception\InternalErrorException;

/**
 * Csv component
 */
class ExcelComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Delimitador archivo.
     *
     * @var array
     **/
    private $delimiter = ';';

    /**
     * Archivo que actualmente se está procesando.
     *
     * @var string 
     **/
    private $file = null;

    /**
     * Mapeo del archivo actual.
     *
     * @var array
     **/
    private $mapping = [];

    /**
     * Obtiene el delimitador del archivo
     *
     * @return string
     **/
    public function getDelimiter()
    {
    	return $this->delimiter;
    }

    /**
     * Setea el delimitador del archivo
     *
     * @return self
     **/
    public function setDelimiter($delimiter)
    {
    	$this->delimiter = $delimiter;

    	return $this;
    }

    /**
	 * Setea el archivo a subir.
	 *
	 * @return self
	 * @throws \Cake\Network\Exception\InternalErrorException Cuando el archivo no existe.
	 **/
	public function setFile($file)
	{
		if (is_file($file) === false) {
			throw new InternalErrorException('El archivo ingresado no existe.');
		}

		$this->file = $file;

		return $this;
	}

	/**
	 * Setea un mapeo para archivo.
	 *
	 * @param array $mapping
	 *
	 * @return self
	 * @throws \Cake\Network\Exception\InternalErrorException Cuando no existe un mapeo.
	 **/
	public function setMapping($mapping)
	{
		if (empty($mapping)) {
			throw new InternalErrorException('Debe ingresar un mapeo.');
		}

		$this->mapping = $mapping;

		return $this;
	}

	/**
	 * Obtiene el archivo actual que se está procesando.
	 *
	 * @return string
	 * @throws \Cake\Network\Exception\InternalErrorException Cuando no existe un archivo.
	 **/
	public function getFile()
	{
		if ($this->file === null) {
			throw new InternalErrorException('Ingrese un archivo para generar el mapeo.');
		}

		if (is_file($this->file) === false) {
			throw new InternalErrorException('El archivo ingresado no existe.');
		}

		return $this->file;
	}

	/**
	 * Obtiene el mapeo del archivo actual.
	 *
	 * @return array
	 * @throws \Cake\Network\Exception\InternalErrorException Cuando no existe un mapeo.
	 **/
	public function getMapping()
	{
		if (empty($this->mapping)) {
			throw new InternalErrorException('No existe un mapeo.');
		}

		return $this->mapping;
	}

	/**
	 * Procesa el archivo indicado
	 *
	 * @return array
	 * @throws \Cake\Network\Exception\InternalErrorException Cuando el handler no se puede obtener.
	 **/
	public function process()
	{
		$handle = @fopen($this->getFile(), 'r');

		if ($handle === false) {
			throw new InternalErrorException('No se puede leer el archivo especificado.');
		}

		$row = 0;
		$data = [];
		$regs = [];

		while ( ($rows = fgetcsv($handle, 0, ';')) !== false) {
			if (++$row === 1) {
				continue;
			}

			foreach ($this->getMapping() as $key => $value) {
				$rowValue = 0;

				if (isset($rows[ $key ])) {
					$rowValue = $rows[ $key ];
				}

				// Order is important!.
				$utf8_encoded = utf8_encode($rowValue);
				$filtered = htmlentities($utf8_encoded);
				$decoded = html_entity_decode($filtered);
				$trimmed = trim($decoded);

				$data[ $value ] = $trimmed;
			}

			$regs[] = $data;
		}

		fclose($handle);

		return $regs;
	}
}
