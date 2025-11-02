INSERT INTO
    users (
        role_id,
        username,
        email,
        password,
        first_name,
        last_name
    )
VALUES (
        1,
        'admin',
        'admin@example.com',
        '$2y$12$opZJaJp/5e9Pba5AGrlrcum3m6n0wrS4boJLNHV3T6vpbd43iB/W2',
        'System',
        'Admin'
    ),
    (
        2,
        'manager1',
        'manager1@example.com',
        '$2y$12$opZJaJp/5e9Pba5AGrlrcum3m6n0wrS4boJLNHV3T6vpbd43iB/W2',
        'Jane',
        'Doe'
    ),
    (
        3,
        'employee1',
        'employee1@example.com',
        '$2y$12$opZJaJp/5e9Pba5AGrlrcum3m6n0wrS4boJLNHV3T6vpbd43iB/W2',
        'John',
        'Smith'
    );