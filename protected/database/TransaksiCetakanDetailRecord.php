<?php
/**
 * Auto generated by prado-cli.php on 2008-03-18 12:28:13.
 */
class TransaksiCetakanDetailRecord extends TActiveRecord
{
	const TABLE='tbt_cetakan_detail';

	public $id;
	public $id_transaksi;
	public $id_cetakan;
	public $nm_cetakan;
	public $hrg_cetakan
	public $jumlah_pesanan;
	public $est_hari;
	public $tt_hari;
	public $lembur;
	public $subtotal;
	
	public $deleted;
	
	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>
