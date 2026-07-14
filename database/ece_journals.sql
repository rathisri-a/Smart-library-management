-- Corrected SQL Insert Script for ece_journals
CREATE TABLE IF NOT EXISTS ece_journals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  journal_title VARCHAR(255),
  publisher VARCHAR(255),
  subject_area VARCHAR(255),
  journal_type VARCHAR(100)
);

INSERT INTO ece_journals (journal_title, publisher, subject_area, journal_type) VALUES
('A Closer Look at Access Control in Multi-User Voice Systems', 'IEEE', 'Computer Science / Information Security / Smart Systems / Human-Computer Interaction', 'Open Access'),
('Research and Analysis of the Front-end Frameworks and Libraries in E-Business Development', 'Association for Computing Machinery (ACM)', 'Computer Science / Web Development / E-Business / Front-end Technologies', 'Conference Proceedings (Peer-reviewed ACM publication)'),
('An Overview of Voice Conversion and Its Challenges: From Statistical Modeling to Deep Learning', 'IEEE/ACM', 'Artificial Intelligence / Speech Processing / Machine Learning / Deep Learning / Voice Conversion', 'Peer-reviewed, Open Access'),
('Online Public Access Catalogue (OPAC) Usage Patterns Among the Library Users of Odisha: A Study', 'IAEME Publication', 'Library and Information Science / Information Management / Digital Library Systems', 'Peer-reviewed Journal'),
('A Review on Methods for Speech-to-Text and Text-to-Speech Conversion', 'IRJET', 'Computer Science / Speech Processing / Artificial Intelligence / Machine Learning', 'Open Access, Peer-reviewed'),
('A Study on the Effective Use of Online Public Access Catalogue at the Libraries of Engineering Colleges in Karnataka (India)', 'Academic Journals', 'Library and Information Science / Information Technology / Library Automation', 'Peer-reviewed, Open Access'),
('Smart Voice Assistant for Library System', 'IRJMT', 'Computer Science / Library Automation / Internet of Things (IoT) / Speech Processing', 'Peer-reviewed');
