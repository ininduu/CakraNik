# CakraNik: Urban Circular Economy Platform for Organic Waste Exchange

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![SDGs](https://img.shields.io/badge/SDGs-11_%26_12-E5243B?style=for-the-badge)](https://sdgs.un.org/)

> **ANFORCOM 2026 Diponegoro Software Development Competition (DSDC)**  
> **Theme:** *Engineering the Circular City: Software Solutions for a Sustainable and Healthy Urban Future*[cite: 4]  
> **Sub-theme:** *Smart Waste & Resource Circularity Systems*[cite: 4]  
> 🔗 **Demo Video:** [YouTube Demo](https://youtu.be/UK9nowKAHbY)
> 🔗 **Repository:** [GitHub Repository](https://github.com/ininduu/CakraNik.git)

---

## 📌 1. The Problem: Food Loss and Waste (FLW)

Indonesia faces severe environmental and socioeconomic challenges driven by unmanaged food loss and waste[cite: 2]:
* **National Impact:** National FLW accumulates between **38 and 48 million tons per year** (115–184 kg/capita/year), causing economic losses estimated at **IDR 213–551 trillion annually**[cite: 2]. Data from SIPSN KLHK (2025) confirms that **food waste accounts for 40.76% of total national waste**, significantly surpassing plastic waste[cite: 2].
* **Local Urban Crisis (Semarang City):** Semarang generated **434,243 tons of waste annually**, the highest volume in Central Java[cite: 1, 2]. Consequently, the Jatibarang Landfill experiences severe overcapacity[cite: 2]. Meanwhile, **41.33% of this municipal organic waste is recoverable** for productive applications such as animal feed, Black Soldier Fly (BSF) larvae cultivation, and organic compost production[cite: 3].
* **The Information & Coordination Gap:** Supply and demand exist simultaneously, but lack an institutional bridge[cite: 3]. The HoReCa sector (Hotels, Restaurants, Catering) incurs waste disposal costs and lacks coordinated channels to offload organic byproducts[cite: 1, 3, 5]. Conversely, urban farmers and BSF maggot breeders face persistent feedstock shortages[cite: 1, 2, 3].

---

## 💡 2. The Solution: CakraNik

**CakraNik** is a bilateral digital platform engineered to connect organic waste providers (**Supply**) with urban agricultural and bioconversion actors (**Demand**)[cite: 1, 5]. Acting as a "circular broker," CakraNik transforms linear food disposal pathways into a structured, accountable circular economy network[cite: 1, 3, 23].

### Core Value Propositions:
* **Targeted Matching Engine:** An algorithmic recommendation system pairing listings based on material category, geographic location, and required volume.
* **Verifiable Transaction Lifecycle:** A structured exchange pipeline (Request ➔ Review/Approval ➔ Photo-verified Delivery Confirmation) preventing fraudulent claims and ensuring chain of custody[cite: 1, 4, 16].
* **Auditable Circular Dashboard:** Quantitative metrics tracking diverted organic waste measured in standard units (kilograms, liters, sacks)[cite: 1, 16].

---

## ⚙ 3. Software Engineering Aspects

CakraNik is architected using domain-driven and clean code principles to guarantee reliability, data integrity, and modular scalability[cite: 11, 12].

### A. Development Methodology
Engineered using an **Agile Iterative Framework**:
1. **Requirements Analysis:** Comprehensive elicitation of Functional Requirements (FR-01 to FR-12) and Non-Functional Requirements (Performance target $<3$s response time, Role-Based Access Control, Data Reliability)[cite: 7, 10, 11, 12].
2. **System & Data Modeling:** Conceptualized through Use Case diagrams, Entity Relationship Diagrams (ERD), and 3NF database normalization schemes[cite: 6, 13, 14].
3. **Iterative Sprint Implementation:** Incremental development across authentication, material cataloging, transaction state machines, notifications, and analytics modules[cite: 6, 7].
4. **Verification & Refinement:** Systematic unit and end-to-end user scenario testing, database query indexing optimization for transaction logs, timezone standardizations, and upload payload validation.

### B. System Architecture & Data Flow
The platform implements the standard **Model-View-Controller (MVC)** architectural pattern within the Laravel framework[cite: 6, 15]:
* **HTTP Routing & Security Middleware:** Incoming requests pass through role validation middleware enforcing Role-Based Access Control (RBAC).
* **Controller Layer:** Coordinates domain business logic, pagination, and recommendation filters.
* **Data Access & Persistence (Eloquent ORM):** Interacts with normalized MySQL relational tables via indexed queries for optimal throughput[cite: 15, 16].
* **Notification Layer:** Utilizes Laravel's internal database notification channel for state change events.
* **Storage Abstraction:** Decoupled storage filesystem architecture (prepared for seamless migration from local disk storage to cloud-native S3 object storage)[cite: 15, 16, 23].

```text
[ Browser / Client ]
         │
   (HTTP Request)
         ▼
[ Web Routes & RBAC Middleware ]
         │
         ▼
[ Controller Layer (Business Logic) ] ───► [ Recommendation Engine ]
         │                                          │
         ├───► [ Eloquent Models ] ──► [ MySQL DB (Indexed) ]
         │
         ├───► [ Filesystem Layer ] ──► [ Local Disk / S3 Object Storage ]
         │
         ▼
[ Blade Views + Tailwind CSS Engine ]
