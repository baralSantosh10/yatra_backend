
CREATE TABLE IF NOT EXISTS api_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    token VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS Users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE not null,
    password VARCHAR(100) not null,
    profilePicture VARCHAR(100),
    contact VARCHAR(10),
    birthdate DATE,
    userType VARCHAR(50) NOT NULL
);



CREATE TABLE IF NOT EXISTS Organizers (
    organizerId INT AUTO_INCREMENT PRIMARY KEY,
    eventCount INT,
    user_id int not null
      FOREIGN KEY (user_id) REFERENCES users(user_id),
);

CREATE TABLE IF NOT EXISTS EventCategory (
    catId INT AUTO_INCREMENT PRIMARY KEY,
    eventType VARCHAR(20) NOT NULL,
    categoryName VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Venue (
    venueId INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(100) NOT NULL,
    capacity INT NOT NULL,
    venuePhoto VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS Events (
    eventId INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    date DATE NOT NULL,
    time VARCHAR not null,
    venuePhoto VARCHAR,
    image VARCHAR(150),
    vipSeatCapacity int,
    regularSeatCapacity int,
    pricePerVIP double(10, 2),
    pricePerRegular double (10, 2),
    venueId INT NOT NULL,
    organizerId INT NOT NULL,
    catId INT NOT NULL,
    FOREIGN KEY (venueId) REFERENCES Venue(venueId),
    FOREIGN KEY (organizerId) REFERENCES Organizers(organizerId),
    FOREIGN KEY (catId) REFERENCES EventCategory(catId)
);

CREATE TABLE IF NOT EXISTS EventTickets (
    ticketID INT AUTO_INCREMENT PRIMARY KEY,
    price DECIMAL(10, 2) NOT NULL,
    ticketType VARCHAR(10) NOT NULL,
    quantity INT NOT NULL,
    eventId INT NOT NULL,
    FOREIGN KEY (eventId) REFERENCES Events(eventId)
);
CREATE TABLE IF NOT EXISTS TicketCategories (
    categoryId INT AUTO_INCREMENT PRIMARY KEY,
    categoryName VARCHAR(50) NOT NULL
);


CREATE TABLE IF NOT EXISTS userEventParticipation (
    E_Id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    eventId INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES Users(userId),
    FOREIGN KEY (eventId) REFERENCES Events(eventId)
);

CREATE TABLE IF NOT EXISTS Payment (
    paymentId INT AUTO_INCREMENT PRIMARY KEY,
    paymentType VARCHAR(20) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    paymentToken VARCHAR(255),
    userId INT NOT NULL,
    ticketId INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES Users(userId),
    FOREIGN KEY (ticketId) REFERENCES EventTickets(ticketID)
);

CREATE TABLE IF NOT EXISTS pollSession (
    pollId INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255),
    vote BOOLEAN,
    userId INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES Users(userId)
);
