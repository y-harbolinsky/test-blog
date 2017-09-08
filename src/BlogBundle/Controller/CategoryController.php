<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
use BlogBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     * @Template()
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('BlogBundle:Category')->findAll();

        return ['categories' => $categories];
    }

    /**
     * Creates a new category entity.
     * @Template()
     *
     */
    public function createAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('BlogBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }

        return [
            'category' => $category,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a category entity.
     * @Template()
     *
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return [
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
	        'comments' => $category->getComments(),
        ];
    }

	/**
	 * Finds and displays a category entity.
	 * @Template()
	 *
	 */
	public function postsAction(Category $category)
	{
		return [
			'category' => $category,
			'posts' => $category->getPosts(),
		];
	}

    /**
     * Displays a form to edit an existing category entity.
     * @Template()
     *
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('BlogBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_edit', array('id' => $category->getId()));
        }

        return [
            'category' => $category,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a category entity.
     *
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
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

			$categoryId = $request->get('id');
			$category = $em->getRepository('BlogBundle:Category')->find($categoryId);

			$author = $request->get('author');
			$content = $request->get('comment');

			$comment = new Comment();
			$comment->setAuthor($author);
			$comment->setContent($content);
			$category->addComment($comment);

			$em->persist($comment);
			$em->flush();

			return new JsonResponse([
				'author' => $comment->getAuthor(),
				'content' => $comment->getContent(),
			]);
		}

		return $this->redirectToRoute('category_show', ['id' => $request->get('id')]);
	}

}
