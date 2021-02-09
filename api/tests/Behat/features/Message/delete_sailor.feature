Feature:
    Delete Sailor

    Scenario: Delete a Sailor
        When I send a "DELETE" request to "/api/sailor" with body:
        """
        {
            "email": "sailor1@aperturedevs.com"
        }
        """
        Then the response status code should be 200
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "id" should exist
        And the JSON node "id" should not be null

    Scenario: Delete a Sailor which don't exists
        When I send a "DELETE" request to "/api/sailor" with body:
        """
        {
            "email": "unknownsailor@aperturedevs.com"
        }
        """
        Then the response status code should be 404
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "title" should exist
        And the JSON node "title" should be equal to 'Resource Not Found'
        And the JSON node "description" should exist
        And the JSON node "description" should be equal to 'Resource "sailor" with property "email" and value "unknownsailor@aperturedevs.com" not found.'
        And the JSON node "status" should exist
        And the JSON node "status" should be equal to 404

    Scenario: Delete a Sailor already deleted
        When I send a "DELETE" request to "/api/sailor" with body:
        """
        {
            "email": "sailor2@aperturedevs.com"
        }
        """
        Then the response status code should be 404
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "title" should exist
        And the JSON node "title" should be equal to 'Resource Not Found'
        And the JSON node "description" should exist
        And the JSON node "description" should be equal to 'Resource "sailor" with property "email" and value "sailor2@aperturedevs.com" not found.'
        And the JSON node "status" should exist
        And the JSON node "status" should be equal to 404

    Scenario: Delete a Sailor without body
        When I send a "DELETE" request to "/api/sailor"
        Then the response status code should be 400
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "title" should exist
        And the JSON node "title" should be equal to 'Invalid Request'
        And the JSON node "description" should exist
        And the JSON node "description" should be equal to 'email : This value should not be blank.'
        And the JSON node "status" should exist
        And the JSON node "status" should be equal to 400
