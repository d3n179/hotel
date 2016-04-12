<?PHP
class MasterKamar extends MainConf
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
			$sqlJnsKamar = "SELECT id,nama FROM tbm_jenis_kamar WHERE deleted = '0' ";
			$arrJnsKamar = $this->queryAction($sqlJnsKamar,'S');
			$this->DDJnsKamar->DataSource = $arrJnsKamar;
			$this->DDJnsKamar->DataBind();
			
			$sqlBed = "SELECT id,nama FROM tbm_bed WHERE deleted = '0' ";
			$arrBed = $this->queryAction($sqlBed,'S');
			$this->DDBed->DataSource = $arrBed;
			$this->DDBed->DataBind();
			
			$tblBody = $this->BindGrid();
			$this->getPage()->getClientScript()->registerEndScript
						('','
						jQuery("#table-1 tbody").append("'.$tblBody.'");');	
		}
	}
	
	public function BindGrid()
	{
		$sql = "SELECT 
					tbm_kamar.id,
					tbm_kamar.id_jns_kamar,
					tbm_kamar.nomor_kamar,
					tbm_kamar.lantai,
					tbm_kamar.blok,
					tbm_kamar.max_tamu,
					tbm_kamar.st_smoking
				FROM 
					tbm_kamar
				WHERE 
					tbm_kamar.deleted = '0' 
				ORDER BY 
					tbm_kamar.id ASC ";
		$Record = $this->queryAction($sql,'S');
		
		$count = count($Record);
		$tblBody = '';
		if($count > 0)
		{
			$no = 1;
			foreach($Record as $row)
			{
				$jnsKamar = JenisKamarRecord::finder()->findByPk($row['id_jns_kamar'])->nama;
				
				if($row['st_smoking'] == '0')
					$stSmoking = 'Tidak';
				else
					$stSmoking = 'Ya';
					
				$tblBody .= '<tr>';
				$tblBody .= '<td>'.$no.'</td>';
				$tblBody .= '<td>'.$row['nomor_kamar'].'</td>';
				$tblBody .= '<td>'.$jnsKamar.'</td>';
				$tblBody .= '<td>'.$row['lantai'].'</td>';
				$tblBody .= '<td>'.$row['blok'].'</td>';
				$tblBody .= '<td>'.$row['max_tamu'].'</td>';
				$tblBody .= '<td>'.$stSmoking.'</td>';
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
		$Record = KamarRecord::finder()->findByPk($id);
		if($Record)
		{
			$this->modalJudul->Text = 'Edit Kamar';
			$this->idKamar->Value = $id;
			$this->nomor_kamar->Text = $Record->nomor_kamar;
			$this->lantai->Text = $Record->lantai;
			$this->blok->Text = $Record->blok;
			$this->max_tamu->Text = $Record->max_tamu;
			$this->DDJnsKamar->SelectedValue = $Record->id_jns_kamar;
			$this->StSmoking->SelectedValue = $Record->st_smoking;
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
		$Record = KamarRecord::finder()->findByPk($id);
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
		if($this->idKamar->Value != '')
		{
			$Record= KamarRecord::finder()->findByPk($this->idKamar->Value);
			$msg = "Data Berhasil Diedit";
		}
		else
		{
			$Record = new KamarRecord();
			$msg = "Data Berhasil Disimpan";
		}
		
		$Record->nomor_kamar = $this->nomor_kamar->Text;
		$Record->lantai = $this->lantai->Text;
		$Record->blok = $this->blok->Text;
		$Record->max_tamu = $this->max_tamu->Text;
		$Record->id_jns_kamar = $this->DDJnsKamar->SelectedValue;
		$Record->st_smoking = $this->StSmoking->SelectedValue;
		$Record->save(); 
		
		$this->idKamar->Value = "";
		$this->nomor_kamar->Text = "";
		$this->lantai->Text = "";
		$this->blok->Text = "";
		$this->max_tamu->Text = "";
		$this->DDJnsKamar->SelectedValue = "empty";
		$this->StSmoking->SelectedValue = "empty";
		
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
