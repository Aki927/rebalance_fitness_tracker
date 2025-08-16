/*
ReBalance Database
Jerome Laranang, July 1, 2025
*/
DROP DATABASE IF EXISTS fitness_tracker;
CREATE DATABASE fitness_tracker;
USE fitness_tracker;

CREATE TABLE users
(
    user_id    INT AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(50)        NOT NULL,
    last_name  VARCHAR(50)        NOT NULL,
    email      VARCHAR(50)        NOT NULL,
    password   VARCHAR(255)       NOT NULL,
    PRIMARY KEY (user_id)
);

INSERT INTO users (user_id, first_name, last_name, email, password)
VALUES (1002, 'Kelly', 'Irvin', 'kelly@example.com', 'sesame'),
       (1004, 'Kenzie', 'Quinn', 'kenzie@jobtrak.com', 'sesame'),
       (1006, 'Anton', 'Mauro', 'amauro@yahoo.org', 'sesame'),
       (1008, 'Kaitlyn', 'Anthoni', 'kanthoni@pge.com', 'sesame'),
       (1010, 'Kendall', 'Mayte', 'kmayte@fresno.ca.gov', 'sesame'),
       (1012, 'Marvin', 'Quintin', 'marvin@expedata.com', 'sesame');

CREATE TABLE workout_day
(
    day_type_id INT         NOT NULL,
    day_name    VARCHAR(30) NOT NULL,
    PRIMARY KEY (day_type_id)
);

INSERT INTO workout_day (day_type_id, day_name)
VALUES (1, 'Chest'),
       (2, 'Back'),
       (3, 'Legs'),
       (4, 'Shoulders'),
       (5, 'Biceps'),
       (6, 'Triceps'),
       (7, 'Abs');

CREATE TABLE primary_muscle
(
    muscle_id   INT         NOT NULL,
    name        VARCHAR(50) NOT NULL,
    day_type_id INT         NOT NULL,
    PRIMARY KEY (muscle_id),
    FOREIGN KEY (day_type_id) REFERENCES workout_day (day_type_id)
);

INSERT INTO primary_muscle (muscle_id, name, day_type_id)
VALUES (1, 'Chest', 1),
       (2, 'Back', 2),
       (3, 'Legs', 3),
       (4, 'Shoulders', 4),
       (5, 'Biceps', 5),
       (6, 'Triceps', 6),
       (7, 'Abs', 7);

CREATE TABLE exercises
(
    exer_id   INT          AUTO_INCREMENT NOT NULL,
    name      VARCHAR(100)                NOT NULL,
    muscle_id INT                         NOT NULL,
    img_url   VARCHAR(255),
    PRIMARY KEY (exer_id),
    FOREIGN KEY (muscle_id) REFERENCES primary_muscle (muscle_id)
);

INSERT INTO exercises (exer_id, name, muscle_id, img_url)
VALUES (1, 'Crunch', 7, 'https://wger.de/media/exercise-images/91/Crunches-1.png'),
       (2, 'Decline Crunch', 7, 'https://wger.de/media/exercise-images/93/Decline-crunch-1.png'),
       (3, 'Hyperextensions', 2, 'https://wger.de/media/exercise-images/128/Hyperextensions-1.png'),
       (4, 'Narrow Grip Bench Press', 1, 'https://wger.de/media/exercise-images/88/Narrow-grip-bench-press-1.png'),
       (5, 'Front Squat', 3, 'https://wger.de/media/exercise-images/191/Front-squat-1-857x1024.png'),
       (6, 'Incline Cable Fly', 1, 'https://wger.de/media/exercise-images/122/Incline-cable-flyes-1.png'),
       (7, 'Tricep Press', 6, 'https://wger.de/media/exercise-images/84/Lying-close-grip-triceps-press-to-chin-1.png'),
       (8, 'Bench Dip', 6, 'https://wger.de/media/exercise-images/83/Bench-dips-1.png'),
       (9, 'Rear Delt Row', 2, 'https://wger.de/media/exercise-images/109/Barbell-rear-delt-row-2.png'),
       (10, 'Leg Raise', 7, 'https://wger.de/media/exercise-images/125/Leg-raises-1.png'),
       (11, 'Cable Seated Row', 2, 'https://wger.de/media/exercise-images/143/Cable-seated-rows-2.png'),
       (12, 'T-Bar Row', 2, 'https://wger.de/media/exercise-images/106/T-bar-row-1.png'),
       (13, 'Cross Body Crunch', 7, 'https://wger.de/media/exercise-images/176/Cross-body-crunch-1.png'),
       (14, 'Seated Barbell Shoulder Press', 4, 'https://wger.de/media/exercise-images/119/seated-barbell-shoulder-press-large-2.png'),
       (15, 'Dumbbell Shoulder Press', 4, 'https://wger.de/media/exercise-images/123/dumbbell-shoulder-press-large-1.png'),
       (16, 'Dumbbell Biceps Curl', 5, 'https://wger.de/media/exercise-images/81/Biceps-curl-1.png'),
       (17, 'Lying Leg Curl', 3, 'https://wger.de/media/exercise-images/154/lying-leg-curl-machine-large-2.png'),
       (18, 'Hammer Curl with Rope', 5, 'https://wger.de/media/exercise-images/138/Hammer-curls-with-rope-2.png'),
       (19, 'Chin-up', 2, 'https://wger.de/media/exercise-images/181/Chin-ups-1.png'),
       (20, 'Preacher Curl', 5, 'https://wger.de/media/exercise-images/193/Preacher-curl-3-1.png'),
       (21, 'Incline Press', 1, 'https://wger.de/media/exercise-images/16/Incline-press-1.png'),
       (22, 'Barbell Shrug', 4, 'https://wger.de/media/exercise-images/150/Barbell-shrugs-1.png'),
       (23, 'Pec Machine', 1, 'https://wger.de/media/exercise-images/98/Butterfly-machine-2.png'),
       (24, 'Hack Squat', 3, 'https://wger.de/media/exercise-images/130/Narrow-stance-hack-squats-1-1024x721.png'),
       (25, 'Cable Crossover', 1, 'https://wger.de/media/exercise-images/71/Cable-crossover-2.png'),
       (26, 'Dead Lifts', 2, 'https://wger.de/media/exercise-images/161/Dead-lifts-2.png'),
       (27, 'Narrow bench Press', 1, 'https://wger.de/media/exercise-images/238/2fc242d3-5bdd-4f97-99bd-678adb8c96fc.png'),
       (28, 'Front Dumbbell Raise', 4, 'https://wger.de/media/exercise-images/256/b7def5bc-2352-499b-b9e5-fff741003831.png'),
       (29, 'Side Delt Raise', 4, 'https://wger.de/media/exercise-images/349/9d969203-9cb6-4d47-9c31-fef53bfe1de5.png'),
       (30, 'Bent Over Tricep Pull', 6,'https://wger.de/media/exercise-images/659/a60452f1-e2ea-43fe-baa6-c1a2208d060c.png'),
       (31, 'Standing Bicep Curl', 5, 'https://wger.de/media/exercise-images/129/Standing-biceps-curl-2.png'),
       (32, 'Bench Press', 1, 'https://wger.de/media/exercise-images/192/Bench-press-2.png'),
       (33, 'Shoulder Press Machine', 4, 'https://wger.de/media/exercise-images/53/Shoulder-press-machine-1.png');

CREATE TABLE workouts
(
    workout_id   INT AUTO_INCREMENT NOT NULL,
    user_id      INT                NOT NULL,
    workout_date DATE               NOT NULL,
    PRIMARY KEY (workout_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

INSERT INTO workouts (workout_id, user_id, workout_date)
VALUES (1, 1002, '2025-07-01'),
       (2, 1004, '2025-07-02'),
       (3, 1006, '2025-07-03'),
       (4, 1008, '2025-07-04'),
       (5, 1010, '2025-07-05');

CREATE TABLE exercise_set
(
    set_id         INT AUTO_INCREMENT NOT NULL,
    workout_id     INT                NOT NULL,
    exer_id        INT                NOT NULL,
    weight_lifted  DECIMAL(10, 2)     NOT NULL,
    reps_completed INT                NOT NULL,
    PRIMARY KEY (set_id),
    FOREIGN KEY (workout_id) REFERENCES workouts (workout_id) ON DELETE CASCADE,
    FOREIGN KEY (exer_id) REFERENCES exercises (exer_id) ON DELETE CASCADE
);

INSERT INTO exercise_set (set_id, workout_id, exer_id, weight_lifted, reps_completed)
VALUES (1, 2, 12, 58.54, 15),
       (2, 3, 18, 97.72, 8),
       (3, 2, 31, 185.97, 12),
       (4, 4, 9, 44.6, 12),
       (5, 3, 22, 112.02, 15),
       (7, 1, 12, 49.01, 8),
       (8, 1, 7, 85.08, 8),
       (10, 5, 9, 165.93, 12),
       (11, 4, 23, 104.51, 15),
       (12, 2, 22, 52.23, 15),
       (14, 5, 30, 181.47, 10),
       (15, 2, 8, 87.38, 15),
       (16, 4, 9, 162.89, 8),
       (18, 3, 6, 128.6, 8),
       (19, 5, 26, 77.17, 12),
       (20, 2, 14, 49.37, 15);


CREATE TABLE administrators
(
    username VARCHAR(40) NOT NULL UNIQUE,
    password VARCHAR(40) NOT NULL,
    PRIMARY KEY (username)
);

INSERT INTO administrators
VALUES ('admin', 'sesame'),
       ('joel', 'sesame'),
       ('root', 'sesame'),
       ('ts_user', 'pa55word');


-- Create a user named ts_user
GRANT SELECT, INSERT, UPDATE, DELETE
    ON *
    TO ts_user@localhost
        IDENTIFIED BY 'pa55word';
