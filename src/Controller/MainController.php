<?php
namespace App\Controller;

use App\Form\PeliculaType;
use App\Repository\PeliculasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController{

    /**
     * @Route("/pelis", name="main")
     */
    public function index(){
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8000/api/peliculas');

        $peliculas = json_decode($response->getContent(), true);

        return $this->render('index.html.twig', [
            'peliculas' => $peliculas,
        ]);
    }

    /**
 * @Route("pelis/modif/{id}", name="modif_pelicula")
 */
    public function modifPelicula($id, Request $request){
        $client = HttpClient::create();

        $form = $this->createForm(PeliculaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $auxPelicula = [];
            if(!empty($form->get('nombre')->getData())){
                $auxPelicula['nombre'] = $form->get('nombre')->getData();
            }
            if(!empty($form->get('genero')->getData())){
                $auxPelicula['genero'] = $form->get('genero')->getData();
            }
            if(!empty($form->get('descripcion')->getData())){
                $auxPelicula['descripcion'] = $form->get('descripcion')->getData();
            }
            $client->request('PUT', 'http://localhost:8000/api/peliculas/'.$id, [
                'json' => $auxPelicula
            ]);

            return $this->redirectToRoute('main');
        }

        $response = $client->request('GET', 'http://localhost:8000/api/peliculas/'.$id);
        $pelicula = json_decode($response->getContent(), true);

        $form->get('nombre')->setData($pelicula['nombre']);
        $form->get('genero')->setData($pelicula['genero']);
        $form->get('descripcion')->setData($pelicula['descripcion']);

        return $this->render('modif.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("pelis/add", name="add_pelicula")
     */
    public function addPelicula(Request $request){
        $client = HttpClient::create();

        $form = $this->createForm(PeliculaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !empty($form->get('nombre')->getData()) && !empty($form->get('genero')->getData())
        && !empty($form->get('descripcion')->getData()) ) {
            $auxPelicula = [];
            $auxPelicula['nombre'] = $form->get('nombre')->getData();
            $auxPelicula['genero'] = $form->get('genero')->getData();
            $auxPelicula['descripcion'] = $form->get('descripcion')->getData();

            $client->request('POST', 'http://localhost:8000/api/peliculas', [
                'json' => $auxPelicula
            ]);

            return $this->redirectToRoute('main');
        }

        return $this->render('modif.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("pelis/elim/{id}", name="elim_pelicula")
     */
    public function elimPelicula($id){
        $client = HttpClient::create();

        $client->request('DELETE', 'http://localhost:8000/api/peliculas/'.$id);

        return $this->redirectToRoute('main');

    }
}