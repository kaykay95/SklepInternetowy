<?php
/**
 * Created by PhpStorm.
 * User: Klaudia.Kwiatkowska
 * Date: 10.07.2019
 * Time: 02:06
 */

namespace App\Tests\Controller;

use App\Controller\NegotiationSummaryController;
use PHPUnit\Framework\TestCase;


class NegotiationSummaryControllerTest extends TestCase
{
    public function testCheckIfNegotiationExpressionIsCorrect(){
        $controller = New NegotiationSummaryController();
        $expression = 'Jestem stałym klientem';
        $correctExpression = true;
        $category = 2;
        $expected = array($correctExpression, $category);
        $result = $controller->checkIfNegotiationExpressionIsCorrect($expression);
        $this->assertEquals($expected[1], $result[1]);
        $this->assertEquals($expected[2], $result[2]->getId());
    }

    public function createNegotiation(){

    }
}