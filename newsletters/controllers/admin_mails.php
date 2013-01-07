<?php if(!defined('BASEPATH'))exit('No direct script access allowed'); 
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Newsletter
 */
class Admin_mails extends Admin_Controller {

    private $data;
    protected $section = 'newsletters';
	// Validation rules to be used for create and edit
	
	private $newsletter_rules = array(
       array(
             'field'   => 'subject',
             'label'   => 'Subject',
             'rules'   => 'trim|required'
          ),
       array(
             'field'   => 'body',
             'label'   => 'Body',
             'rules'   => 'trim|required'
          )
    );


	public function __construct()
	{
		parent::__construct();
		$this->load->model('newsletter_m','newsletters');
		$this->lang->load('newsletters');
        $this->data = new stdClass();
	}
	

	//prevent duplicate emails
	function _check_email($str,$id)
	{
		$query = $this->db->get_where('newsletter_recipients', array('email'=>$str));
		if ($query->num_rows() == 0)
		{
			return true;
		}
		elseif($query->row()->id == $id and $query->num_rows() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// Admin: Different actions
	function action($table)
	{
		switch($this->input->post('btnAction'))
		{
			case 'trash':
				$this->newsletters->delete($table,$this->input->post('action_to'));
			break;
			case 'restore':
				$this->newsletters->delete($table,$this->input->post('action_to'),true);
			break;
			case 'delete':
				$this->newsletters->delete($table,$this->input->post('action_to')) or die('wwww');
			break;
		}
		$table=='recipients' ? $redirect='recipients' : $redirect='';//get more specific
		redirect('admin/newsletters/'.$redirect);
	}


	function index($type='draft')
	{
		$this->data->type=$type;
		$this->data->newsletters=$this->newsletters->get_newsletters($type);
        
		$this->template
		->build('admin/mails',$this->data);
	}


	function preview($id)
	{
		foreach($this->newsletters->get_newsletters(false,$id) as $mail)
		{//move to views with template header/footer
			echo 'Subject: '.$mail->subject;
			echo '<a href="/admin/newsletters/send_mail/'.$id.'/preview">Send me a preview</a>';
			echo '<hr />';
			echo $mail->body;
		}
	}
	
	function create()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->newsletter_rules);

        if($this->form_validation->run())
        {
            $input = $this->input->post();
            $input['active'] = 1;
            if($this->newsletters->create($input))
            {
                $this->session->set_flashdata('success', lang('newsletters_create_success'));
                redirect('admin/newsletters/mails');
            }
            else
            {
                $this->session->set_flashdata('error', lang('newsletters_edit_error'));
                redirect('admin/newsletters/mails/create');
            }
        }
        
        foreach ($this->newsletter_rules AS $rule)
        {
            $rule['field'] = $this->input->post($rule['field']);
        }
		$this->template->append_metadata($this->load->view('fragments/wysiwyg',$this->data,TRUE));		
		$this->template->build('admin/mail_form', $this->data);
	}

	function edit($id=false)
	{
		if($id) $this->data->newsletter=$this->newsletters->get_newsletters(false,$id);
        
        $this->form_validation->set_rules($this->newsletter_rules);
        if($this->form_validation->run())
        {
            unset($_POST['btnAction']);
            $input = $this->input->post();
            $input['modified'] = date("Y-m-d H:i:s");
            
            if($this->newsletters->update($id, $input))
            {
                // All good...
                $this->session->set_flashdata('success', lang('newsletters_edit_success'));
                redirect('admin/newsletters/mails/edit/'.$id);
            }
            else
            {
                $this->session->set_flashdata('error', lang('newsletters_edit_error'));
                redirect('admin/newsletters/mails/edit');
            }
        }
        
		
		$this->template->append_metadata($this->load->view('fragments/wysiwyg',$this->data,TRUE));		
		$this->template->build('admin/mail_form', $this->data);
	}

	

	function confirm_send($id)
	{
		foreach($this->newsletters->get_newsletters('draft',$id) as $mail)
		{
			$this->data->mail_id=$mail->id;
			$this->data->subject=$mail->subject;
			$this->data->body=$mail->body;
		}
		$this->data->groups=$this->newsletters->groups();
		$this->template->build('admin/confirm_send',$this->data);
	}


	
	function send_mail($id,$preview=false)
	{
		$preview='preview' ? $preview==true : $preview==false;
		$this->newsletters->send($id,$preview);
	}


function add_users_from_file(){$this->newsletters->add_users_from_file();}

}