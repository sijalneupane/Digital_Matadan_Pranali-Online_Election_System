## 📝 This is project done for BCA 4th Semester

**Project Name:** Digital Matadan Pranali (Online Election System)  
**Developers:** Sijal Neupane & Simrika Raul  
**Institution:** Tribhuvan University (TU)  
**Semester:** BCA 4th Semester  
**Date:** April 08, 2025  

---

### 🌐 It is about an online election system where users can register, wait for verification by admin, and then vote in time if account is verified.

Digital Matadan Pranali is an online election platform inspired by the **First Past the Post (FPTP)** method used in Nepal’s parliamentary elections. For now, it covers only the **Bagmati Province**. This system allows citizens to participate in elections digitally with secure registration, admin verification, and time-bound voting.

---

## ✨ Features

- **🔔 Election Scheduling:** Admin schedules elections and generates PDF notices.  
- **📋 Candidate Management:** Admin adds party details and registers candidates during nomination.  
- **👤 Voter Registration:** Users register with citizenship details (number, front/back photos), a personal photo, and personal info (age 18+ required).  
- **✅ Admin Verification:** Admin approves/rejects registrations; approved voters get a Voter ID via email, rejected ones get a reason.  
- **🗳️ Voting:** Verified voters log in with their Voter ID and vote during the voting period only. A message appears outside this timeframe.  
- **📊 Results:** Admin publishes results after voting ends; viewable by all.  
- **🔍 Public Access:** Candidate lists, notices, and results can be viewed without logging in, with search functionality.

---

## 🛠️ Technologies Used

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **APIs & Tools:**  
  - **Abstract API ✉️** - Email validation and verification.  
  - **MailMaster 📧** - Sending emails (Voter ID, status updates).  
  - **FPDF 📄** - Generating PDF notices.

---

## 🚀 How to Set Up

You can either **run the project locally by cloning** or **visit the live site** to explore it directly.

### Option 1: Run Locally
#### Requirements:
- Web server (e.g., XAMPP) with PHP.  
- MySQL database.  
- API keys for Abstract API and MailMaster.  

#### Steps:
1. Clone/download the project from the repository.  
2. Place it in your server’s root folder (e.g., `htdocs`).  
3. Set up the MySQL database (tables for voters, candidates, etc.).  
4. Update database credentials in PHP files.  
5. Add API keys to the configuration for Abstract Api.  
6. Run the server and visit `http://localhost/[project-folder]`.

### Option 2: Visit Live Site
- Access the deployed version at: [https://sjnp.tech](https://sjnp.tech)  
  *(Replace this URL with the actual site link if available.)*

---

## 📖 How It Works

1. **Election Setup:** Admin schedules the election (PDF notice generated).  
2. **Nomination:** Admin registers parties and candidates.  
3. **Registration:** Users sign up with citizenship and personal details.  
4. **Verification:** Admin approves/rejects; emails are sent accordingly.  
5. **Voting:** Voters log in and vote during the allowed period.  
6. **Results:** Admin publishes results post-voting.

---

## ⚠️ Limitations

- Covers only Bagmati Province.  
- Basic security features; no advanced encryption yet.

---

## 🌟 Future Plans

- Expand to all Nepal provinces.  
- Add real-time vote tracking and visuals.  
- Enhance security with OTP or biometric login.

---

## 👥 Team

- **Sijal Neupane** - BCA 4th Sem, TU  
- **Simrika Raul** - BCA 4th Sem, TU