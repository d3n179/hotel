<?php
/**
 * Auto generated by prado-cli.php on 2008-03-18 12:28:13.
 */
class KategoriCetakanRecord extends TActiveRecord
{
	const TABLE='tbm_kategori_cetakan';

	public $id;
	public $nama;
	public $deleted;
	
	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>