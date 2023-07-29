<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommentRepository $commentRepository, int $id, Comment $comment): Response
    {
        $user = $this->getUser();

        if (!$user || $user !== $comment->getUser()) {
            $this->addFlash(
                'notice',
                'You can only edit your own comments.'
            );
        }

        $comment = $commentRepository->find($id);

        // check if the user is the author of the comment or an administrator
        // $this->denyAccessUnlessGranted('EDIT', $comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment, true);

            return new JsonResponse(['message' => 'Success!'], 200);
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('comment/_edit_form.html.twig', [
                'comment' => $comment,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_advice_show', ['id' => $comment->getCommentAdvice()->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository, int $id): Response
    {
        $user = $this->getUser();

        if (!$user || $user !== $comment->getUser()) {
            $this->addFlash(
                'notice',
                'You can only delete your own comments.'
            );
        }

        $comment = $commentRepository->find($id);

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_advice_show', ['id' => $comment->getCommentAdvice()->getId()]);
        // return $this->redirectToRoute('app_advice_show', ['id' => $id]);
    }
}
