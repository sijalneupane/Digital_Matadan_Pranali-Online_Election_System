--1) //pending status table and voters table
CREATE TABLE pendingstatus (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    email VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    password VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    dateOfBirth DATE NULL,
    citizenshipNumber VARCHAR(50) COLLATE latin1_swedish_ci NULL,
    gender VARCHAR(50) COLLATE latin1_swedish_ci NULL,
    addressId INT(11) NOT NULL,
    citizenshipFrontPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    citizenshipBackPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    userPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    status VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL DEFAULT 'pending',
    FOREIGN KEY (addressId) REFERENCES localaddress(lid) ON DELETE CASCADE
);

--2) voters table:
CREATE TABLE voters (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    email VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    password VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL,
    dateOfBirth DATE NULL,
    citizenshipNumber VARCHAR(50) COLLATE latin1_swedish_ci NULL,
    gender VARCHAR(50) COLLATE latin1_swedish_ci NULL,
    addressId INT(11) NOT NULL,
    citizenshipFrontPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    citizenshipBackPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    userPhoto VARCHAR(255) COLLATE latin1_swedish_ci NULL,
    votingStatus ENUM('notVoted', 'voted') DEFAULT 'notVoted',
    FOREIGN KEY (addressId) REFERENCES localaddress(lid) ON DELETE CASCADE
);
-- ALTER TABLE voters
-- ADD votingStatus ENUM('notVoted', 'voted') DEFAULT 'notVoted';

-- 3)//district table
create table district(
    dId int AUTO_INCREMENT PRIMARY KEY,
    district varchar(100) not NULL,
    regionNo varchar(255) not null
)


-- 4)//localaddress table
CREATE TABLE localaddress (
    lid INT AUTO_INCREMENT PRIMARY KEY,
    dId INT NOT NULL,
    local_address VARCHAR(255) NOT NULL,
    FOREIGN KEY (dId) REFERENCES district(dId) ON DELETE CASCADE
);

-- 5)//candidates table
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

-- 6)//parties table
CREATE TABLE parties (
    partyId INT AUTO_INCREMENT PRIMARY KEY,
    partyName VARCHAR(255) NOT NULL UNIQUE,
    partyLeader VARCHAR(255) NOT NULL UNIQUE,
    partyLogo VARCHAR(255) NOT NULL UNIQUE
);
INSERT INTO parties (partyName, partyLeader, partyLogo) 
VALUES 
('Independent', 'No Leader', 'independent.png');
-- the independent.png is located in images folder

-- 7)//election time table
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


--8) //currentresults table 
CREATE TABLE currentresults (
    electionId INT NOT NULL,
    electionName VARCHAR(50) NOT NULL,
    candidateName VARCHAR(255) NOT NULL,
    citizenshipNumber VARCHAR(50) NOT NULL,
    partyName VARCHAR(255),
    dId INT,
    totalVotes INT DEFAULT 0,
    PRIMARY KEY (electionId, candidateName, citizenshipNumber),
    FOREIGN key(dId) REFERENCES district(dId)
);

--9) //archive results table(holds total election results till now altogether) 
CREATE TABLE archiveresults (
    electionId INT NOT NULL,
    electionName VARCHAR(50) NOT NULL,
    candidateName VARCHAR(255) NOT NULL,
    citizenshipNumber VARCHAR(50) NOT NULL,
    partyName VARCHAR(255),
    dId INT,
    totalVotes INT DEFAULT 0,
    PRIMARY KEY (electionId, candidateName, citizenshipNumber),
    FOREIGN key(dId) REFERENCES district(dId)
);