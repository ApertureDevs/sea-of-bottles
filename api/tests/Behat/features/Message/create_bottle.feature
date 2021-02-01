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

    Scenario: Create a Bottle with invalid message
        When I send a "POST" request to "/api/bottle" with body:
        """
        {
            "message": ""
        }
        """
        Then the response status code should be 400
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "title" should exist
        And the JSON node "title" should be equal to 'Invalid Command'
        And the JSON node "description" should exist
        And the JSON node "description" should be equal to 'Message content cannot be empty.'
        And the JSON node "status" should exist
        And the JSON node "status" should be equal to 400
