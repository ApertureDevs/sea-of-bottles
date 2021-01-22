Feature:
    Create Bottle

    Scenario: Create a Bottle
        When I send a "POST" request to "/api/bottle" with body:
        """
        {
            "message": "Hello-World"
        }
        """
        Then the response status code should be 201
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "id" should exist
        And the JSON node "id" should not be null
