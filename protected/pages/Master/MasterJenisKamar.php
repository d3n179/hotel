<?PHP
class MasterJenisKamar extends MainConf
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
					tbm_jenis_kamar.id,
					tbm_jenis_kamar.nama
				FROM 
					tbm_jenis_kamar
				WHERE 
					tbm_jenis_kamar.deleted = '0' 
				ORDER BY 
					tbm_jenis_kamar.id ASC ";
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
		$Record = JenisKamarRecord::finder()->findByPk($id);
		if($Record)
		{
			$this->modalJudul->Text = 'Edit Jenis Kamar';
			$this->idJnsKamar->Value = $id;
			$this->nama->Text = $Record->nama;
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
		$Record = JenisKamarRecord::finder()->findByPk($id);
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
		if($this->idJnsKamar->Value != '')
		{
			$Record= JenisKamarRecord::finder()->findByPk($this->idJnsKamar->Value);
			$msg = "Data Berhasil Diedit";
		}
		else
		{
			$Record = new JenisKamarRecord();
			$msg = "Data Berhasil Disimpan";
		}
		
		$Record->nama = $nama;
		$Record->save(); 
		
		$this->nama->Text = '';
		
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
