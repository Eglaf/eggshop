<?php

namespace App\Controller\Admin\Content;

use App\Egf\Ancient\AbstractController;
use App\Egf\Util;
use App\Entity\Content\File;
use App\Form\Admin\Content\FileType as FileFormType;
use App\Service\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FileController
 */
class FileController extends AbstractController {
	
	/**
	 * List of Files.
	 *
	 * RouteName: app_admin_content_file_list
	 * @Route("/admin/file/list")
	 * @Template
	 *
	 * @param Serializer $serializer Service to convert entities into json.
	 * @return array
	 */
	public function listAction(Serializer $serializer) {
		$files = $this->getDm()->getRepository(File::class)->findAll();
		
		return [
			'listAsJson' => $serializer->toJson($files),
			'uploadsDir' => Util::slashing($this->getParameter('app.uploads_load_directory'), Util::slashingAddRight),
		];
	}
	
	/**
	 * Browse for CkEditor.
	 *
	 * RouteName: app_admin_content_file_browse
	 * @Route("/admin/file/browse")
	 * @Template
	 *
	 * @return Response|array
	 */
	public function browseAction() {
		return [
			'path'   => Util::slashing($this->getParameter('app.uploads_load_directory'), Util::slashingAddRight),
			'images' => $this->getDm()->getRepository(File::class)->findAll(),
		];
	}
	
	/**
	 * Upload a via CkEditor form.
	 *
	 * RouteName: app_admin_content_file_upload
	 * @Route("/admin/file/upload")
	 * @Template
	 *
	 * @return Response|array
	 */
	public function uploadAction() {
		try {
			$uploadedFile = $this->getRq()->files->get('upload');
			$fileName     = $this->saveUploadedFile($uploadedFile);
			
			$file = (new File())
				->setActive(TRUE)
				->setStorageName($fileName)
				->setMimeType($uploadedFile->getClientMimeType());
			
			$this->getDm()->persist($file);
			$this->getDm()->flush();
			
			// Response for CkEditor.
			return [
				'file' => Util::slashing($this->getParameter('app.uploads_load_directory'), Util::slashingAddRight) . $fileName,
			];
		}
		catch (\Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Create a File.
	 *
	 * RouteName: app_admin_content_file_create
	 * @Route("/admin/file/create")
	 * @Template("admin/content/file/form.html.twig")
	 *
	 * @return array|RedirectResponse
	 */
	public function createAction() {
		return $this->form(new File());
	}
	
	/**
	 * Update a File File.
	 *
	 * RouteName: app_admin_content_file_update
	 * @Route("/admin/file/update/{file}", requirements={"file"="\d+|_id_"})
	 * @Template("admin/content/file/form.html.twig")
	 *
	 * @param File $file
	 * @return array|RedirectResponse
	 */
	public function updateAction(File $file) {
		$file->setFile(new \Symfony\Component\HttpFoundation\File\File("{$this->getParameter('app.uploads_save_directory')}/{$file->getStorageName()}"));
		
		return $this->form($file);
	}
	
	/**
	 * Generate form view to File File.
	 * @param File $file
	 * @return array|RedirectResponse
	 */
	protected function form(File $file) {
		// Create form.
		$form = $this->createForm(FileFormType::class, $file);
		$form->handleRequest($this->getRq());
		
		// Save form.
		if ($form->isSubmitted() && $form->isValid()) {
			/** @var UploadedFile $uploadedFile */
			$uploadedFile = $file->getFile();
			
			if ($uploadedFile instanceof UploadedFile) {
				/** @var string $fileName Storage name of file. */
				$fileName = $this->saveUploadedFile($uploadedFile);
				
				// Set the real storage file name.
				$file->setStorageName($fileName)
				     ->setMimeType($uploadedFile->getClientMimeType());
			}
			
			$this->getDm()->persist($file);
			$this->getDm()->flush();
			
			return $this->redirectToRoute('app_admin_content_file_list');
		}
		
		// Form view.
		return [
			'file'       => $file,
			'formView'   => $form->createView(),
			'uploadsDir' => Util::slashing($this->getParameter('app.uploads_load_directory'), Util::slashingAddRight),
		];
	}
	
	/**
	 * Save the uploaded file and gives back the storageName.
	 * @param UploadedFile $uploadedFile
	 * @return string Storage name of uploaded file.
	 */
	protected function saveUploadedFile(UploadedFile $uploadedFile) {
		// Generate a unique name for the file before saving it
		$fileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
		
		// Move the file to the uploads directory.
		$uploadedFile->move($this->getParameter('app.uploads_save_directory'), $fileName);
		
		return $fileName;
	}
	
}