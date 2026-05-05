CREATE DATABASE IF NOT EXISTS myDB;
USE myDB;
-- Table 1: users
CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    first_name NVARCHAR(50) NOT NULL,
    last_name NVARCHAR(50) NOT NULL,
    email NVARCHAR(100) NOT NULL UNIQUE,
    phone_number NVARCHAR(20) NOT NULL UNIQUE,
    password_hash NVARCHAR(3000) NOT NULL,
    date_of_birth DATE NOT NULL,
    security_question NVARCHAR(255) NOT NULL,
    security_question_answer NVARCHAR(3000) NOT NULL,
    security_code NVARCHAR(100) NULL,
    profile_picture NVARCHAR(255) NULL,
    account_status ENUM('active', 'suspended', 'banned', 'deactivated') NOT NULL DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP
);
-- Table 2: rides
CREATE TABLE rides (
    ride_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pickup_location NVARCHAR(255) NOT NULL,
    pickup_lat DECIMAL(10, 7) NOT NULL,
    pickup_long DECIMAL(10, 7) NOT NULL,
    dropoff_location NVARCHAR(255) NOT NULL,
    dropoff_lat DECIMAL(10, 7) NOT NULL,
    dropoff_long DECIMAL(10, 7) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    carplate_number NVARCHAR(20) NOT NULL,
    vehicle_model NVARCHAR(100) NOT NULL,
    pickup_time DATETIME NOT NULL,
    available_seats INT NOT NULL,
    status ENUM('active', 'ongoing', 'completed', 'closed') NOT NULL DEFAULT 'active',
    completed_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);
-- Table 3: ride_participants
CREATE TABLE ride_participants (
    participant_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ride_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('active', 'left', 'completed') NOT NULL DEFAULT 'active',
    joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME NULL,
    FOREIGN KEY (ride_id) REFERENCES rides (ride_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    UNIQUE (ride_id, user_id)
);
-- Table 4: ratings
CREATE TABLE ratings (
    rating_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ride_id INT NOT NULL,
    rater_user_id INT NOT NULL,
    rated_user_id INT NOT NULL,
    rating_score TINYINT NOT NULL CHECK (
        rating_score BETWEEN 1 AND 5
    ),
    description TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ride_id) REFERENCES rides (ride_id),
    FOREIGN KEY (rater_user_id) REFERENCES users (user_id),
    FOREIGN KEY (rated_user_id) REFERENCES users (user_id),
    CHECK (rater_user_id <> rated_user_id),
    UNIQUE (ride_id, rater_user_id, rated_user_id)
);
-- Table 5: ride_chat_rooms
CREATE TABLE ride_chat_rooms (
    ride_chat_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ride_id INT NOT NULL,
    guest_user_id INT NOT NULL,
    status ENUM('waiting', 'active', 'closed', 'timeout') NOT NULL DEFAULT 'waiting',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    closed_at DATETIME NULL,
    FOREIGN KEY (ride_id) REFERENCES rides (ride_id),
    FOREIGN KEY (guest_user_id) REFERENCES users (user_id)
);
-- Table 6: ride_chat_messages
CREATE TABLE ride_chat_messages (
    message_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ride_chat_id INT NOT NULL,
    sender_user_id INT NOT NULL,
    message_content TEXT NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ride_chat_id) REFERENCES ride_chat_rooms (ride_chat_id),
    FOREIGN KEY (sender_user_id) REFERENCES users (user_id)
);
-- Table 7: support_chat_rooms
CREATE TABLE support_chat_rooms (
    support_chat_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    customer_user_id INT NOT NULL,
    staff_user_id INT NULL,
    status ENUM('waiting', 'active', 'closed', 'timeout') NOT NULL DEFAULT 'waiting',
    started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    connected_at DATETIME NULL,
    ended_at DATETIME NULL,
    FOREIGN KEY (customer_user_id) REFERENCES users (user_id),
    FOREIGN KEY (staff_user_id) REFERENCES users (user_id)
);
-- Table 8: support_chat_messages
CREATE TABLE support_chat_messages (
    message_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    support_chat_id INT NOT NULL,
    sender_user_id INT NOT NULL,
    message_content TEXT NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (support_chat_id) REFERENCES support_chat_rooms (support_chat_id),
    FOREIGN KEY (sender_user_id) REFERENCES users (user_id)
);
-- Table 9: notifications
CREATE TABLE notifications (
    notification_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message NVARCHAR(500) NOT NULL,
    status ENUM('unread', 'read', 'error') NOT NULL DEFAULT 'unread',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);
INSERT INTO `users` (
        `user_id`,
        `role_id`,
        `first_name`,
        `last_name`,
        `email`,
        `phone_number`,
        `password_hash`,
        `date_of_birth`,
        `security_question`,
        `security_question_answer`,
        `security_code`,
        `profile_picture`,
        `account_status`,
        `created_at`,
        `updated_at`
    )
VALUES (
        '1',
        '1',
        'Ahmad',
        'Razif',
        'ahmad.razif@email.com',
        '+60123456789',
        '$2b$12$KIXaBcDeFgHiJkLmNoPqRsTuVwXyZ',
        '1995-06-15',
        'What is the name of your first pet?',
        '$2b$12$AnswerHashPlaceholderXYZABC',
        NULL,
        'uploads/profiles/ahmad_razif.jpg',
        'active',
        '2026-04-28 18:18:53',
        NULL
    );
INSERT INTO `users` (
        `user_id`,
        `role_id`,
        `first_name`,
        `last_name`,
        `email`,
        `phone_number`,
        `password_hash`,
        `date_of_birth`,
        `security_question`,
        `security_question_answer`,
        `security_code`,
        `profile_picture`,
        `account_status`,
        `created_at`,
        `updated_at`
    )
VALUES (
        '2',
        '2',
        'Siti',
        'Aminah',
        'siti.aminah@email.com',
        '+60198765432',
        '$2b$12$AnotherHashPlaceholderXYZABC',
        '1998-03-22',
        'What is your mother maiden name?',
        '$2b$12$AnswerHashPlaceholder2XYZABC',
        NULL,
        'uploads/profiles/siti_aminah.jpg',
        'active',
        '2026-04-28 18:18:53',
        NULL
    );
INSERT INTO `rides` (
        `ride_id`,
        `user_id`,
        `pickup_location`,
        `pickup_lat`,
        `pickup_long`,
        `dropoff_location`,
        `dropoff_lat`,
        `dropoff_long`,
        `price`,
        `pickup_time`,
        `available_seats`,
        `status`,
        `completed_at`,
        `created_at`,
        `updated_at`
    )
VALUES (
        '1',
        '1',
        'Sunway Pyramid, Petaling Jaya',
        '3.0738000',
        '101.6010000',
        'KL Sentral, Kuala Lumpur',
        '3.1338000',
        '101.6861000',
        '8.00',
        '2026-05-27 08:30:00',
        '3',
        'active',
        '2026-05-28 21:29:52',
        '2026-04-28 18:18:53',
        '2026-05-04 21:30:30'
    );
INSERT INTO `rides` (
        `ride_id`,
        `user_id`,
        `pickup_location`,
        `pickup_lat`,
        `pickup_long`,
        `dropoff_location`,
        `dropoff_lat`,
        `dropoff_long`,
        `price`,
        `pickup_time`,
        `available_seats`,
        `status`,
        `completed_at`,
        `created_at`,
        `updated_at`
    )
VALUES (
        '3',
        '1',
        'KLIA T1, KLIA Departure, Sepang, 64000, Malaysia',
        '2.7547334',
        '101.7046397',
        'Serampang Hill, Malaysia',
        '2.3621814',
        '102.7844764',
        '1.00',
        '2026-05-07 00:00:00',
        '3',
        'active',
        '2026-05-08 21:30:40',
        '2026-05-02 23:47:42',
        '2026-05-04 21:30:46'
    );
INSERT INTO `ride_participants` (
        `participant_id`,
        `ride_id`,
        `user_id`,
        `status`,
        `joined_at`,
        `completed_at`
    )
VALUES (
        '1',
        '1',
        '2',
        'active',
        '2026-04-28 09:00:00',
        NULL
    );
INSERT INTO `ratings` (
        `rating_id`,
        `ride_id`,
        `rater_user_id`,
        `rated_user_id`,
        `rating_score`,
        `description`,
        `created_at`
    )
VALUES (
        '1',
        '1',
        '1',
        '2',
        '5',
        'Very punctual and friendly driver. Car was clean and comfortable.',
        '2026-04-28 18:18:53'
    );
INSERT INTO `ride_chat_rooms` (
        `ride_chat_id`,
        `ride_id`,
        `guest_user_id`,
        `status`,
        `created_at`,
        `closed_at`
    )
VALUES (
        '1',
        '1',
        '1',
        'active',
        '2026-04-28 09:05:00',
        NULL
    );
INSERT INTO `ride_chat_messages` (
        `message_id`,
        `ride_chat_id`,
        `sender_user_id`,
        `message_content`,
        `sent_at`
    )
VALUES (
        '1',
        '1',
        '1',
        'Hi! I am on my way. Will be there in 5 mins.',
        '2026-04-28 09:10:00'
    );
INSERT INTO `support_chat_rooms` (
        `support_chat_id`,
        `customer_user_id`,
        `staff_user_id`,
        `status`,
        `started_at`,
        `connected_at`,
        `ended_at`
    )
VALUES (
        '1',
        '1',
        '1',
        'active',
        '2026-04-28 10:00:00',
        '2026-04-28 10:01:30',
        NULL
    );
INSERT INTO `support_chat_messages` (
        `message_id`,
        `support_chat_id`,
        `sender_user_id`,
        `message_content`,
        `sent_at`
    )
VALUES (
        '1',
        '1',
        '1',
        'Hello, I would like to report an issue with my ride post.',
        '2026-04-28 10:02:00'
    );
INSERT INTO `notifications` (
        `notification_id`,
        `user_id`,
        `message`,
        `status`,
        `created_at`
    )
VALUES (
        '1',
        '1',
        'Your ride post "Sunway Pyramid to KL Sentral" has been updated by staff.',
        'unread',
        '2026-04-28 11:00:00'
    );