Feature:
    Create Sailor

    Scenario: Create a Sailor
        When I send a "POST" request to "/api/sailor" with body:
        """
        {
            "email": "newsailor@aperturedevs.com"
        }
        """
        Then the response status code should be 201
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "id" should exist
        And the JSON node "id" should not be null

    Scenario: Create a Sailor with already exist
        When I send a "POST" request to "/api/sailor" with body:
        """
        {
            "email": "sailor1@aperturedevs.com"
        }
        """
        Then the response status code should be 400
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "title" should exist
        And the JSON node "title" should be equal to 'Domain Error'
        And the JSON node "description" should exist
        And the JSON node "description" should be equal to 'Sailor with email "sailor1@aperturedevs.com" already exists.'
        And the JSON node "status" should exist
        And the JSON node "status" should be equal to 400

    Scenario: Create a Sailor with invalid email exist
        When I send a "POST" request to "/api/sailor" with body:
        """
        {
            "email": "aperturedevs.com"
        }
        """
        Then the response status code should be 400
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "title" should exist
        And the JSON node "title" should be equal to 'Invalid Request'
        And the JSON node "description" should exist
        And the JSON node "description" should be equal to 'email : This value is not a valid email address.'
        And the JSON node "status" should exist
        And the JSON node "status" should be equal to 400

    Scenario: Create a Sailor without body
        When I send a "POST" request to "/api/sailor"
        Then the response status code should be 400
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "title" should exist
        And the JSON node "title" should be equal to 'Invalid Request'
        And the JSON node "description" should exist
        And the JSON node "description" should be equal to 'email : This value should not be blank.'
        And the JSON node "status" should exist
        And the JSON node "status" should be equal to 400
