<?php 
class BackupsController extends AppController
{
	var $name = "Backups";
	var $helpers = array('Html', 'Form');
		
	function restore()
	{
		$this->helpers[] = "Time";
		$this->helpers[] = "Number";
		$this->pageTitle = "Restore";
		
		//$data = $this->paginate('Backup');
		//$this->set(compact('data'));
		
		$this->set('backups', $this->Backup->find('all', array('conditions' => array('user_id' => $this->Session->read('Auth.User.id')))));
	}
	
	/**
	 * Add file to backup
	 */
	 function add() 
	 {
	 	$this->pageTitle = "Backup";
		
		if (!empty($this->data))
		{
			foreach($this->data['Backup'] as $file)
			{
				if(is_uploaded_file($file['File']['tmp_name']))
				{
					$zip = zip_open($file['File']['tmp_name']);
					
					if(is_resource($zip))
					{
						while ($zip_entry = zip_read($zip))
						{
							$this->Backup->create();
							
							$this->data['Backup']['name'] = zip_entry_name($zip_entry);	
							$this->data['Backup']['size'] = zip_entry_filesize($zip_entry);
							$this->data['Backup']['data'] = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							$this->data['Backup']['hash'] = md5($this->data['Backup']['data']);
							$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
							
							$this->Backup->save($this->data);
						}
					}
					else
					{
						$this->Backup->create();

						$this->data['Backup']['name'] = $file['File']['name'];
						$this->data['Backup']['size'] = $file['File']['size'];
						$this->data['Backup']['data'] = fread(fopen($file['File']['tmp_name'], "r"), $file['File']['size']);
						$this->data['Backup']['hash'] = md5($this->data['Backup']['data']);
						$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
						
						$this->Backup->save($this->data);
					}
				}
			}
			
			$this->Session->setFlash('The selected files have been backed up.');
			$this->redirect('/backups/restore');
		}
    }

	function download($id) 
	{
		Configure::write('debug', 0);
		$file = $this->Backup->findById($id);
	
		header('Content-type: ' . $file['Backup']['type']);
		header('Content-length: ' . $file['Backup']['size']);
		header('Content-Disposition: attachment; filename="'.$file['Backup']['name'].'"');
		echo $file['Backup']['data'];
		exit();
	}
	
	function view($id)
	{
		Configure::write('debug', 0);
		$file = $this->Backup->findById($id);
	
		header('Content-type: ' . $file['Backup']['type']);
		header('Content-length: ' . $file['Backup']['size']);
		header('Content-Disposition: inline; filename="'.$file['Backup']['name'].'"');
		echo $file['Backup']['data'];
		exit();
	}
	
	function delete($id)
	{
		$this->Backup->del($id);
		$this->flash('The file has been deleted.', '/backups/restore');
	}
}
?>