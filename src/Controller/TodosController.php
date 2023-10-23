<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//pour attribuer une route par prefixer pour tout le methode

#[Route('/todos')]

class TodosController extends AbstractController
{
    #[Route('/', name: 'todos')]
    public function index(Request $request): Response
    {
        //creation d'une session
        $session = $request->getSession();

        //verification si la session existe ou pas 
        if (!$session->has('todos')) {
            $todos = [
                'achat' => 'tabltte',
                'sport' => 'football',
                'divertis' => 'playstation',
                'cours' => 'java & springboot'
            ];
            $session->set('todos', $todos);
            //message de validation apres la creation de la session
            $this->addFlash('info', "la liste est init");
        }


        return $this->render('todos/index.html.twig', [
            'controller_name' => 'TodosController',
        ]);
    }
    #[Route('/add/{name}/{content}', name: 'todos.add')]
    public function addTodo(Request $request, $name, $content): RedirectResponse
    {
        //creation de la session
        $session = $request->getSession();
        //verification de l'existance de la session
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            //verification de l'existance de la cle dans la session deja creer
            //isset verifie si la variable existe
            if (isset($todos[$name])) {
                //au cas de l'existance de la clée dans la session returner une erreur
                $this->addFlash('error', "la clée $name existe deja");
            } else {
                //au cas contraire creer la todo avec la clée et la valeur
                $todos[$name] = $content;
                $this->addFlash('success', "le todo $name a ete ajouté avec succèss");
                //actualiser le tableau de todoq
                $session->set('todos', $todos);
            }
        } else {

            $this->addFlash('error', "la liste de todo est vide");
        }
        //redirection vers la function index(la creation de la session)
        return $this->redirectToRoute('todos');
    }

    //mettre a jour un element du todo

    #[Route('/update/{name}/{content}', name: 'todos.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse
    {
        //creation de la session
        $session = $request->getSession();
        //verification de l'existance de la session
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            //verification de l'existance de la cle dans la session deja creer
            //isset verifie si la variable existe
            if (!isset($todos[$name])) {
                //au cas de l'existance de la clée dans la session returner une erreur
                $this->addFlash('error', "la clée $name existe deja");
            } else {
                //au cas contraire creer la todo avec la clée et la valeur
                $todos[$name] = $content;
                $this->addFlash('success', "le todo $name a ete modifié avec succèss");
                //actualiser le tableau de todoq
                $session->set('todos', $todos);
            }
        } else {

            $this->addFlash('error', "la liste de todo est vide");
        }
        //redirection vers la function index(la creation de la session)
        return $this->redirectToRoute('todos');
    }

    //supprimer un todo a partir du nom

    #[Route('/delete/{name}', name: 'todos.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse
    {
        //creation de la session
        $session = $request->getSession();
        //verification de l'existance de la session
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            //verification de l'existance de la cle dans la session deja creer
            //isset verifie si la variable existe
            if (!isset($todos[$name])) {
                //au cas de l'existance de la clée dans la session returner une erreur
                $this->addFlash('error', "la clée $name existe deja");
            } else {
                //au cas contraire creer la todo avec la clée et la valeur
                unset($todos[$name]);
                $this->addFlash('success', "le todo $name a ete supprimé avec succèss");
                //actualiser le tableau de todoq
                $session->set('todos', $todos);
            }
        } else {

            $this->addFlash('error', "la liste de todo est vide");
        }
        //redirection vers la function index(la creation de la session)
        return $this->redirectToRoute('todos');
    }

    //reset la liste de todo


    #[Route('/reset', name: 'todos.reset')]
    public function resetTodo(Request $request): RedirectResponse
    {
        //creation de la session
        $session = $request->getSession();
        $session->remove('todos');
        //redirection vers la function index(la creation de la session)
        return $this->redirectToRoute('todos');
    }
}
