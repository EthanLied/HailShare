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
    issue_type NVARCHAR(255) NOT NULL,
    additional_notes NVARCHAR(255) NOT NULL,
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
-- Users
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
        `account_status`,
        `created_at`,
        `updated_at`
    )
VALUES (
        '1',
        '1',
        'Ahmad',
        'Razif',
        'ahmad.razif@email.co',
        '+60123456789',
        '$2b$12$KIXaBcDeFgHiJkLmNoPqRsTuVwXyZ',
        '1995-01-15',
        'What is your pet&#039;s name?',
        '$2b$12$AnswerHashPlaceholderXYZABC',
        'active',
        '2026-04-28 18:18:53',
        '2026-05-16 21:18:01'
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
        `account_status`,
        `created_at`,
        `updated_at`
    )
VALUES (
        '12',
        '1',
        'Customer',
        'Customer',
        'customer@mail.com',
        '012345678',
        '$2y$10$Vi1XGJ3wtjJJPoSe5kmtb.xNwlU.DHRm06XWCktJf6EAzX57FlB/K',
        '1977-01-01',
        'What is your pet''s name?',
        '1',
        'active',
        '2026-05-22 14:04:46',
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
        `account_status`,
        `created_at`,
        `updated_at`
    )
VALUES (
        '13',
        '2',
        'Staff',
        'Staff',
        'staff@mail.com',
        '0123456789',
        '$2y$10$nWwTr7N/eSR3ZGsfVR0CZOTDfjS.wx25IF/25hvKY33cHTJsovBJC',
        '2006-02-01',
        'What is your pet''s name?',
        '1',
        'active',
        '2026-05-22 14:09:45',
        '2026-05-22 14:09:54'
    );
-- Rides
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
        '0.0000000',
        '0.0000000',
        'KL Sentral, Kuala Lumpur',
        '0.0000000',
        '0.0000000',
        '8.00',
        '2026-05-20 08:30:00',
        '3',
        'active',
        '2026-04-29 00:00:00',
        '2026-04-28 18:18:53',
        '2026-05-13 13:56:37'
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
        'KL Tower Mini Zoo, Jalan Puncak, Kampung Cendana, Kuala Lumpur, 50250, Malaysia',
        '3.1526407',
        '101.7035046',
        'Serampang Hill, Malaysia',
        '2.3621814',
        '102.7844764',
        '12.00',
        '2026-05-19 00:00:00',
        '5',
        'closed',
        '2026-05-20 00:00:00',
        '2026-05-11 22:31:11',
        '2026-05-11 22:45:49'
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
        '4',
        '12',
        'KLCC, Jalan Ampang, Kampung Cendana, Kuala Lumpur, 50088, Malaysia',
        '3.1592469',
        '101.7133662',
        'IOI Puchong Jaya, Damansara–Puchong Expressway, Bandar Puchong Jaya, Subang Jaya City Council, 47170, Malaysia',
        '3.0481365',
        '101.6210724',
        '15.00',
        '2026-05-25 13:00:00',
        '3',
        'active',
        '2026-05-26 00:00:00',
        '2026-05-22 14:05:59',
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
        '5',
        '12',
        'KL Sentral, Jalan Stesen Sentral, Seputeh, Kuala Lumpur, 50470, Malaysia',
        '3.1341106',
        '101.6865153',
        'sSijangkang Dalam Rural Clinic, Jalan Sri Tanjung, Teluk Panglima Garang, Malaysia',
        '2.9326590',
        '101.4348653',
        '13.00',
        '2026-06-04 13:00:00',
        '3',
        'closed',
        '2026-06-05 00:00:00',
        '2026-05-22 14:06:18',
        '2026-05-22 14:06:21'
    );
-- ride_participants, ratings, ride_chat_rooms, ride_chat_messages, support_chat_rooms, support_chat_messages
-- (unchanged — columns already match the CREATE TABLE definitions)
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
        '1',
        'active',
        '2026-04-28 09:00:00',
        NULL
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
        '2',
        '4',
        '12',
        'active',
        '2026-05-22 14:05:59',
        NULL
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
        '3',
        '5',
        '12',
        'completed',
        '2026-05-22 14:06:18',
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
        '2',
        '1',
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
        '6',
        '1',
        '1',
        'active',
        '2026-05-11 21:03:05',
        '2026-05-11 22:10:08'
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
        '7',
        '3',
        '1',
        'active',
        '2026-05-11 22:31:11',
        NULL
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
        '8',
        '4',
        '12',
        'active',
        '2026-05-22 14:05:59',
        NULL
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
        '9',
        '5',
        '12',
        'closed',
        '2026-05-22 14:06:18',
        NULL
    );
INSERT INTO `ride_chat_messages` (
        `message_id`,
        `ride_chat_id`,
        `sender_user_id`,
        `message_content`,
        `sent_at`
    )
VALUES ('9', '6', '2', 'ai', '2026-05-11 22:00:42');
INSERT INTO `ride_chat_messages` (
        `message_id`,
        `ride_chat_id`,
        `sender_user_id`,
        `message_content`,
        `sent_at`
    )
VALUES (
        '14',
        '8',
        '12',
        'Raining now though.',
        '2026-05-22 14:07:42'
    );
INSERT INTO `support_chat_rooms` (
        `support_chat_id`,
        `customer_user_id`,
        `staff_user_id`,
        `status`,
        `started_at`,
        `issue_type`,
        `additional_notes`,
        `ended_at`
    )
VALUES (
        '1',
        '2',
        '1',
        'active',
        '2026-04-28 10:00:00',
        'Issue with specific ride',
        'Sample Note',
        '2026-05-12 22:06:06'
    );
INSERT INTO `support_chat_rooms` (
        `support_chat_id`,
        `customer_user_id`,
        `staff_user_id`,
        `status`,
        `started_at`,
        `issue_type`,
        `additional_notes`,
        `ended_at`
    )
VALUES (
        '2',
        '12',
        '13',
        'active',
        '2026-05-22 14:06:51',
        'Others',
        'Rude ride insulted me',
        '2026-05-22 14:06:54'
    );
INSERT INTO `support_chat_rooms` (
        `support_chat_id`,
        `customer_user_id`,
        `staff_user_id`,
        `status`,
        `started_at`,
        `issue_type`,
        `additional_notes`,
        `ended_at`
    )
VALUES (
        '3',
        '12',
        '13',
        'active',
        '2026-05-22 14:07:01',
        'Ride Issue',
        'Someone complained about me',
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
INSERT INTO `support_chat_messages` (
        `message_id`,
        `support_chat_id`,
        `sender_user_id`,
        `message_content`,
        `sent_at`
    )
VALUES ('7', '1', '1', 'hi', '2026-05-10 18:17:29');