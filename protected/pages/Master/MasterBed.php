<?PHP
class MasterBed extends MainConf
{

	public function onPreRenderComplete($param)
	{
		parent::onPreRenderComplete($param);
		if(!$this->Page->IsPostBack && !$this->Page->IsCallBack)  
		{
			
		}
		
	}
	
	public function onPreRender($param)
	{
		parent::onPreRender($param);
		
		if(!$this->Page->IsPostBack && !$this->Page->IsCallBack)  
		{
			$tblBody = $this->BindGrid();
			$this->getPage()->getClientScript()->registerEndScript
						('','
						jQuery("#table-1 tbody").append("'.$tblBody.'");');	
		}
	}
	
	public function BindGrid()
	{
		$sql = "SELECT 
					tbm_bed.id,
					tbm_bed.nama,
					tbm_bed.harga_extra
				FROM 
					tbm_bed
				WHERE 
					tbm_bed.deleted = '0' 
				ORDER BY 
					tbm_bed.id ASC ";
		$Record = $this->queryAction($sql,'S');
		
		$count = count($Record);
		$tblBody = '';
		if($count > 0)
		{
			$no = 1;
			foreach($Record as $row)
			{
				$tblBody .= '<tr>';
				$tblBody .= '<td>'.$no.'</td>';
				$tblBody .= '<td>'.$row['nama'].'</td>';
				$tblBody .= '<td>'.$row['harga_extra'].'</td>';
				$tblBody .= '<td>';
				$tblBody .= '<a href=\"#\" class=\"btn btn-default btn-sm btn-icon icon-left\" OnClick=\"editClicked('.$row['id'].')\"><i class=\"entypo-pencil\" ></i>Edit</a>&nbsp;&nbsp;';
				$tblBody .= '<a href=\"#\" class=\"btn btn-danger btn-sm btn-icon icon-left\" OnClick=\"deleteClicked('.$row['id'].')\"><i class=\"entypo-cancel\"></i>Delete</a>';		
				$tblBody .=	'</td>';			
				$tblBody .= '</tr>';
				$no++;
			}
		}
		else
		{
			$tblBody = '';
		}
		
		return 	$tblBody;
	}
	
	public function editForm($sender,$param)
	{
		$id = $param->CallbackParameter->id;
		$Record = BedRecord::finder()->findByPk($id);
		if($Record)
		{
			$this->modalJudul->Text = 'Edit Bed';
			$this->idBed->Value = $id;
			$this->nama->Text = $Record->nama;
			$this->harga_extra->Text = $Record->harga_extra;
			$this->getPage()->getClientScript()->registerEndScript
					('','
					unloadContent();
					jQuery("#modal-1").modal("show");
					');	
		}
		else
		{
			$this->getPage()->getClientScript()->registerEndScript
					('','
					unloadContent();
					toastr.error("Data Tidak Ditemukan");
					');	
		}
	}
	
	public function deleteData($sender,$param)
	{
		$id = $param->CallbackParameter->id;
		$Record = BedRecord::finder()->findByPk($id);
		if($Record)
		{
			$Record->deleted = '1';
			$Record->save();
			$tblBody = $this->BindGrid();
			$this->getPage()->getClientScript()->registerEndScript
					('','
					toastr.info("Data Telah Dihapus");
					jQuery("#table-1").dataTable().fnDestroy();
					jQuery("#table-1 tbody").empty();
					jQuery("#table-1 tbody").append("'.$tblBody.'");
					BindGrid();
					unloadContent();
					');
		}
		else
		{
			
			$this->getPage()->getClientScript()->registerEndScript
					('','
					unloadContent();
					toastr.error("Data gagal Dihapus");
					');
		}
	}
	
	public function submitBtnClicked($sender,$param)
	{
		$nama = trim($this->nama->Text);
		$harga_extra = trim($this->harga_extra->Text);
		if($this->idBed->Value != '')
		{
			$Record= BedRecord::finder()->findByPk($this->idBed->Value);
			$msg = "Data Berhasil Diedit";
		}
		else
		{
			$Record = new BedRecord();
			$msg = "Data Berhasil Disimpan";
		}
		
		$Record->nama = $nama;
		$Record->harga_extra = $harga_extra;
		$Record->save(); 
		
		$this->nama->Text = '';
		$this->harga_extra->Text = '';
		
		$tblBody = $this->BindGrid();
		
		$this->getPage()->getClientScript()->registerEndScript
					('','
					toastr.info("'.$msg.'");
					jQuery("#modal-1").modal("hide");
					jQuery("#table-1").dataTable().fnDestroy();
					jQuery("#table-1 tbody").empty();
					jQuery("#table-1 tbody").append("'.$tblBody.'");
					BindGrid();');	
	}
	
	
}
?>
