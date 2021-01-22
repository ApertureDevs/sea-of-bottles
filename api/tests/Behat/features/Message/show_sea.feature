Feature:
    Show the Sea

    Scenario: Show the Sea
        When I send a "GET" request to "/api/sea"
        Then the response status code should be 200
        And the header 'content-type' should be equal to 'application/json'
        And the JSON node "bottles_remaining" should exist
        And the JSON node "bottles_remaining" should be equal to "1"
        And the JSON node "bottles_recovered" should exist
        And the JSON node "bottles_recovered" should be equal to "1"
        And the JSON node "bottles_total" should exist
        And the JSON node "bottles_total" should be equal to "2"
        And the JSON node "sailors_total" should exist
        And the JSON node "sailors_total" should be equal to "1"
