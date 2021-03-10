<?php

namespace App\Tests\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behatch\Context\RestContext;

class ExtendedRestContext extends RestContext
{
    /**
     * @Given I send create Bottle request :count times
     *
     * @param mixed $count
     */
    public function iSendCreateBottleRequestRepeatedly($count)
    {
        $response = null;

        while ($count > 0) {
            $body = new PyStringNode([sprintf('{"message": "%s"}', $this->generateRandomMessage())], 1);
            $response = $this->iSendARequestTo('POST', '/api/bottle', $body);
            --$count;
        }

        return $response;
    }

    /**
     * @Given I send create Sailor request :count times
     *
     * @param mixed $count
     */
    public function iSendCreateSailorRequestRepeatedly($count)
    {
        $response = null;

        while ($count > 0) {
            $body = new PyStringNode([sprintf('{"email": "%s"}', $this->generateRandomEmail())], 1);
            $response = $this->iSendARequestTo('POST', '/api/sailor', $body);
            --$count;
        }

        return $response;
    }

    /**
     * @Given I send create and delete Sailor requests :count times
     *
     * @param mixed $count
     */
    public function iSendCreateAndDeleteSailorRequestsRepeatedly($count)
    {
        $response = null;

        while ($count > 0) {
            $body = new PyStringNode([sprintf('{"email": "%s"}', $this->generateRandomEmail())], 1);
            $this->iSendARequestTo('POST', '/api/sailor', $body);
            $response = $this->iSendARequestTo('DELETE', '/api/sailor', $body);
            --$count;
        }

        return $response;
    }

    private function generateRandomMessage(): string
    {
        return 'This is a random message with generated value '.uniqid();
    }

    private function generateRandomEmail(): string
    {
        return uniqid().'@aperturedevs.com';
    }
}
