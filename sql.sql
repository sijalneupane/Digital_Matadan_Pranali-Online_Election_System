-- 1)election time table
CREATE TABLE electiontime (
    electionId INT(11) NOT NULL AUTO_INCREMENT,
    electionName VARCHAR(50) NOT NULL,
    startTime DATETIME NOT NULL,
    endTime DATETIME NOT NULL,
    nominationStartTime DATETIME NOT NULL,
    nominationEndTime DATETIME NOT NULL,
    resultStatus enum('notPublished','published') DEFAULT 'notPublished',
    PRIMARY KEY (electionId)
);

-- 2)district table
create table district(
    dId int AUTO_INCREMENT PRIMARY KEY,
    district varchar(100) not NULL,
    regionNo varchar(255) not null
)

-- 3)parties table
CREATE TABLE parties (
    partyId INT(11) AUTO_INCREMENT PRIMARY KEY,
    partyName VARCHAR(255) NOT NULL UNIQUE,
    partyLeader VARCHAR(255) NOT NULL UNIQUE,
    partyThemeColor VARCHAR(10) NOT NULL,
    partyLogo VARCHAR(255) NOT NULL UNIQUE
);
INSERT INTO parties (partyName, partyLeader, partyLogo,partyThemeColor) 
VALUES 
('Independent', 'No Leader', 'independent.png','#8f8f8f');
-- the independent.png is located in images folder

-- inserting partyThemeColor in parties table
-- -color:
INde-#8f8f8f
CPN UML-#cc1e1e
COngress-#4CAF50

-- 4)candidates table
CREATE TABLE candidates (
    candidateId INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each candidate
    name VARCHAR(255) NOT NULL,              -- Candidate's full name
    dob DATE NOT NULL,                            -- Candidate's date of birth
    gender ENUM('male', 'female', 'other') NOT NULL, -- Candidate's gender
    citizenship_number VARCHAR(15) NOT NULL,          -- Candidate's contact number
    education_level VARCHAR(100),         -- Candidate's highest education level
    manifesto TEXT,                               -- Candidate's manifesto or campaign description
    partyId INT NOT NULL,                        -- Foreign key linking to parties table
    dId INT NOT NULL,            -- Foreign key linking to districts table
    candidate_photo VARCHAR(255),                  -- Path to candidate's profile photo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of when the record was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Last update time

    -- Foreign Key Constraints
    CONSTRAINT fk_candidate_party FOREIGN KEY (partyId) REFERENCES parties(partyId) ON DELETE CASCADE,
    CONSTRAINT fk_candidate_district FOREIGN KEY (dId) REFERENCES district(dId) ON DELETE CASCADE
);


-- 5) pending status table
CREATE TABLE pendingVoters (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    email VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    password VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    dateOfBirth DATE NULL,
    citizenshipNumber VARCHAR(50) COLLATE latin1_swedish_ci NULL,
    gender VARCHAR(50) COLLATE latin1_swedish_ci NULL,
    dId INT NOT NULL,
    localAddress VARCHAR(255) NOT NULL,
    -- addressId INT(11) NOT NULL,
    citizenshipFrontPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    citizenshipBackPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    userPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    status VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL DEFAULT 'pending',
    FOREIGN KEY (dId) REFERENCES district(dId) ON DELETE CASCADE
);

-- 6) voters table:
CREATE TABLE voters (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    email VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    password VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    dateOfBirth DATE NULL,
    citizenshipNumber VARCHAR(50) COLLATE latin1_swedish_ci NULL,
    gender VARCHAR(50) COLLATE latin1_swedish_ci NULL,
    dId INT NOT NULL,
    localAddress VARCHAR(255) NOT NULL,
    -- addressId INT(11) NOT NULL,
    citizenshipFrontPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    citizenshipBackPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    userPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    votingStatus ENUM('notVoted', 'voted') DEFAULT 'notVoted',
    FOREIGN KEY (dId) REFERENCES district(dId) ON DELETE CASCADE
);

-- 7)voters message table 
CREATE TABLE votersMessages (
    messageId INT AUTO_INCREMENT PRIMARY KEY,  -- Unique primary key
    voterId INT,  -- Foreign key (but not part of the primary key)
    messages TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voterId) REFERENCES voters(id) ON DELETE CASCADE
);


-- 4)//localaddress table
-- CREATE TABLE localaddress (
--     lid INT AUTO_INCREMENT PRIMARY KEY,
--     dId INT NOT NULL,
--     local_address VARCHAR(255) NOT NULL,
--     FOREIGN KEY (dId) REFERENCES district(dId) ON DELETE CASCADE
-- );



-- 8) currentresults table 
CREATE TABLE currentresults ( 
    currentResultId INT AUTO_INCREMENT PRIMARY KEY, 
    electionId INT NOT NULL,
    candidateId INT NOT NULL,
    partyId INT NOT NULL,
    dId INT NOT NULL,
    totalVotes INT DEFAULT 0,
    UNIQUE(electionId, candidateId),
    Foreign key(electionId) references electiontime(electionId) ON DELETE CASCADE,
    -- FOREIGN KEY (candidateId) REFERENCES candidates(candidateId) ON DELETE CASCADE,
    FOREIGN KEY (partyId) REFERENCES parties(partyId) ON DELETE CASCADE,
    FOREIGN KEY (dId) REFERENCES district(dId) ON DELETE CASCADE
);

-- 9) archive results table(holds total election results till now altogether) 
CREATE TABLE archiveresults (
    archiveResultsId INT AUTO_INCREMENT PRIMARY KEY,
    electionId INT NOT NULL,
    electionName VARCHAR(50) NOT NULL,
    candidateName VARCHAR(255) NOT NULL,
    citizenshipNumber VARCHAR(50) NOT NULL,
    partyName VARCHAR(255),
    dId INT, 
    candidatePhoto VARCHAR(255),                  -- Path to candidate's profile photo
    totalVotes INT DEFAULT 0,
    UNIQUE (electionId, candidateName, citizenshipNumber)
);

-- inserting into candidates and currentresults table
-- Insert into Candidates table
INSERT INTO candidates (candidateId, name, dob, gender, citizenship_number, education_level, manifesto, partyId, dId, candidate_photo) 
VALUES 
(1, 'Hari gopal Kc', '1995-01-01', 'male', '123123', "master's degree", 'Passadadasdasdas', 1, 19, '1_1_19_Hari gopal Kc.jpg'),
(2, 'Gokul Baskota', '1994-01-02', 'male', '111111', "master's degree", 'ouuiwerwerwerw', 3, 19, '1_3_19_Gokul Baskota.jpg'),
(3, 'Sangita Raul', '1978-01-01', 'female', '3434343', "master's degree", 'Master 123213123', 2, 19, '1_2_19_Sangita Raul.jpg'),
(4, 'Samish Shrestha', '1976-12-12', 'male', '8765', 'bachelors', 'Hi hello there', 2, 20, '1_2_20_Samish Shrestha.jpg'),
(5, 'dr stone', '1979-01-01', 'male', '12345667789', 'bachelors', 'Hi i will increase these world', 3, 20, '1_3_20_dr stone.jpg'),
(6, 'Saraswoti Ji', '1985-07-06', 'male', '45455454545', 'Engineering', 'Make nepal great again', 1, 19, '1_1_19_Saraswoti Ji.jpg');

-- Insert into CurrentResults table
-- INSERT INTO CurrentResults (electionId, candidateId, partyId, dId,FLOOR(RAND() * 100))
INSERT INTO CurrentResults (electionId, candidateId, partyId, dId)
SELECT 1, candidateId, partyId, dId 
FROM Candidates;
-- update current results
update `currentresults` set totalVotes=FLOOR(RAND() * 1000);

--creating trigger in mysql to update the votingStatus to 'notVoted' in the voters table when new election is added 
DELIMITER //

CREATE TRIGGER set_voting_status
AFTER INSERT ON electionTime
FOR EACH ROW
BEGIN
    UPDATE voters
    SET votingStatus = 'notVoted';
END;
//

DELIMITER ;



