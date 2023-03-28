<?php

namespace App\Controller;

use App\Form\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request): Response
    {
        $data = $request->query->all();
        $curl = curl_init();

        $fname = $data['data']['fname'];
        
        $sname = $data['data']['sname'];

        $url = 'https://love-calculator.p.rapidapi.com/getPercentage?sname='.$sname.'&fname='.$fname;

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "", 
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: love-calculator.p.rapidapi.com",
                "X-RapidAPI-Key: b90d5cc133msh7284256253a5719p1f54aajsn0305c34e8fbc"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $data = json_decode($response);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'data' => $data
        ]);
    }

    #[Route('/create', name: 'app_create')]
    public function createAction(Request $request)
    {
        $form = $this->createForm(FormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('app_home', [
                'data' => $data
            ]);
        }

        return $this->renderForm('home/create.html.twig', [
            'form' => $form,
        ]);

    }
}
