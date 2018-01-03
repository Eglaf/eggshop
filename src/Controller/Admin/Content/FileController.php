<?php

namespace App\Controller\Admin\Content;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Util;
use App\Egf\Ancient\AbstractController;
use App\Entity\Content\File;
use App\Form\Admin\Content\FileType as FileFormType;
use App\Service\Serializer;

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
			/** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
			$uploadedFile = $file->getStorageName();
			
			// Generate a unique name for the file before saving it
			$fileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
			
			// Move the file to the uploads directory.
			$uploadedFile->move($this->getParameter('app.uploads_save_directory'), $fileName);
			
			// Set the real storage file name.
			$file->setStorageName($fileName)
			     ->setOriginalName($uploadedFile->getClientOriginalName())
			     ->setMimeType($uploadedFile->getClientMimeType());
			
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
	 * Upload a via CkEditor form.
	 *
	 * RouteName: app_admin_file_upload
	 * @Route("/admin/file/upload")
	 *
	 * @return Response
	 */
	public function uploadAction() {
		/** @var \Symfony\Component\HttpFoundation\Request $rq */
		$rq = $this->getRq();
		
		$rq->files->get('file');
		
		return new Response("lol?");
	}
	
}