<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
use BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     * @Template()
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('BlogBundle:Post')->findAll();

        return ['posts' => $posts];
    }

    /**
     * Creates a new post entity.
     * @Template()
     *
     */
    public function createAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm('BlogBundle\Form\PostType', $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$file = $post->getFile();
        	if ($file) {
        		$fileName = md5(uniqid()) . '.' . $file->guessExtension();
        		$file->move($this->getParameter('uploads_directory'), $fileName);
        		$post->setFile($fileName);
	        }
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return [
	        'post' => $post,
	        'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a post entity.
     * @Template()
     *
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        return [
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
	        'comments' => $post->getComments(),
        ];
    }

    /**
     * Displays a form to edit an existing post entity.
     * @Template()
     *
     */
    public function editAction(Request $request, Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('BlogBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return [
            'post' => $post,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a post entity.
     *
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Create a comment.
     *
     */
    public function commentAction(Request $request)
    {
    	if ($request->isXmlHttpRequest()) {

		    $em = $this->getDoctrine()->getManager();

    		$postId = $request->get('id');
		    $post = $em->getRepository('BlogBundle:Post')->find($postId);

    		$author = $request->get('author');
    		$content = $request->get('comment');

			$comment = new Comment();
			$comment->setAuthor($author);
			$comment->setContent($content);
		    $post->addComment($comment);

		    $em->persist($comment);
		    $em->flush();

		    return new JsonResponse([
		    	'author' => $comment->getAuthor(),
			    'content' => $comment->getContent(),
		    ]);
	    }

	    return $this->redirectToRoute('post_show', ['id' => $request->get('id')]);
    }

}
