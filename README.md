# CakraNik: Urban Circular Economy Platform for Organic Waste Exchange

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![SDGs](https://img.shields.io/badge/SDGs-11_%26_12-E5243B?style=for-the-badge)](https://sdgs.un.org/)

> **ANFORCOM 2026 — Diponegoro Software Development Competition (DSDC)**  
> **Main Theme:** *Circular Economy for Eco-Health Cities*[cite: 4]  
> **DSDC Theme:** *Engineering the Circular City: Software Solutions for a Sustainable and Healthy Urban Future*[cite: 4]  
> **Sub-theme:** *Smart Waste & Resource Circularity Systems*[cite: 4]  
> 🔗 **Live Demo (YouTube):** [Watch Video Demo](https://youtu.be/UK9nowKAHbY)[cite: 26]  
> 🔗 **GitHub Repository:** [github.com/ininduu/CakraNik](https://github.com/ininduu/CakraNik.git)[cite: 26]

---

## 📖 Table of Contents
1. [Project Overview](#-1-project-overview)
2. [The Problem: Food Loss and Waste (FLW) Crisis](#-2-the-problem-food-loss-and-waste-flw-crisis)
3. [The Solution: CakraNik](#-3-the-solution-cakranik)
4. [Software Engineering Architecture](#-4-software-engineering-architecture)
   - [Development Methodology](#a-development-methodology)
   - [System Architecture & Data Flow](#b-system-architecture--data-flow)
   - [Database Design & Normalization](#c-database-design--normalization)
   - [Technology Stack](#d-technology-stack)
5. [Key System Features](#-5-key-system-features)
6. [Functional & Non-Functional Requirements](#-6-functional--non-functional-requirements)
7. [Measurable Impact Projection](#-7-measurable-impact-projection)
8. [Installation & Local Setup](#-8-installation--local-setup)
9. [Future Roadmap](#-9-future-roadmap)
10. [Authors & Acknowledgments](#-10-authors--acknowledgments)

---

## 🌟 1. Project Overview

**CakraNik** is a bilateral digital platform designed to bridge the structural disconnect between urban organic waste generators and circular resource consumers[cite: 1, 3]. By connecting the hospitality and food service industry (Hotels, Restaurants, Catering/HoReCa) directly with urban agriculture and bioconversion actors (livestock farmers, compost producers, and Black Soldier Fly / BSF maggot cultivators), CakraNik establishes a closed-loop urban ecosystem[cite: 1, 3]. 

The platform facilitates structured material requests, verification workflows, photo-verified physical handovers, dynamic recommendation matching, and quantifiable circular metrics tracking[cite: 1, 4, 16].

---

## ⚠️ 2. The Problem: Food Loss and Waste (FLW) Crisis

Food Loss and Waste (FLW) poses profound environmental hazards, municipal waste crises, and economic losses across Indonesia[cite: 2]:

* **National Waste Accumulation:** According to studies by Bappenas, Waste4Change, and WRI, Indonesia generates between **38 and 48 million tons of FLW per year** (equivalent to 115–184 kg/capita/year)[cite: 2]. This constitutes an estimated economic loss of **IDR 213–551 trillion annually**[cite: 2].
* **Severe Organic Composition:** The National Waste Management Information System (SIPSN KLHK 2025) highlights that **40.76% of Indonesia's 25.14 million tons of total waste consists of food scraps**, dramatically exceeding plastic waste volume[cite: 2]. Business-as-usual trajectories project national food waste could reach 112 million tons/year by 2045[cite: 2].
* **Local Urban Bottleneck (Semarang City):** Semarang generates **434,243 tons of waste annually**—the highest volume in Central Java[cite: 1, 2]. The local Jatibarang Landfill faces extreme overcapacity, with decomposing organic waste emitting severe leachate and greenhouse gases (methane/$CH_4$)[cite: 2, 3].
* **The Structural Coordination Gap:** Simultaneously, **41.33% of municipal waste in Central Java has high residual value** as feedstock for BSF maggot farming and organic composting[cite: 3]. The primary barrier is not an absence of supply or demand, but an **information and transaction gap**[cite: 3]. Without a dedicated digital circular broker, high-value organic matter continues to be sent directly to open landfills[cite: 1, 3].

---

## 💡 3. The Solution: CakraNik

CakraNik acts as a digital **circular broker** that lowers transaction costs, coordinates resource distribution, and formalizes verifiable exchange routines[cite: 3, 4]:

* **Automated Supply-Demand Matching:** Matches available organic waste with specific demand criteria via category, volume requirements, and geographical location.
* **Accountable Handover Chain:** Eliminates informal, unverified waste offloading through a transparent three-step state machine: Request Submission ➔ Supplier Approval/Rejection ➔ Demand Confirmation with Photo Proof.
* **Granular Impact Accounting:** Eliminates generic claims by measuring circular impact per standardized unit (**kilograms**, **liters**, and **sacks**) on dynamic user dashboards.
* **Direct Alignment with Sustainable Development Goals (SDGs):**
  - **SDG 11 (Sustainable Cities and Communities):** Reduces municipal solid waste load, decreases landfill leachate/odor, and promotes urban community resilience.
  - **SDG 12 (Responsible Consumption and Production):** Substantially diverts food waste away from disposal toward high-value bio-economy recycling and feed substitution.

---

## ⚙ 4. Software Engineering Architecture
*Key Architectural Decisions during Sprints:* During testing, the transaction flow was refactored from a four-stage process into a lean, verified three-stage flow with integrated image verification to minimize friction while preserving transaction legitimacy.

---

### A. Development Methodology
The system was engineered following the **Agile Iterative Lifecycle** to allow rapid prototyping and responsive feedback loops

### B. System Architecture & Data Flow
The software is architected using the **Model-View-Controller (MVC)** architectural design pattern on top of Laravel:

```text
[ Browser / Client ]
         │  (HTTP Request)
         ▼
[ Web Routes (routes/web.php) ]
         │
         ▼
[ Role-Based Access Control (RBAC) Middleware ]
         │
         ▼
[ Controllers (TransactionController, MaterialController, etc.) ]
         │                                       │
         ├─────────────────┐                     ▼
         │                 │            [ Recommendation Engine ]
         ▼                 ▼                     │
[ Eloquent Models ]  [ Storage Layer ]           │ (Filtered Suggestions)
         │           (Local / S3 Disk)           │
         │                 │                     │
         ▼                 ▼                     │
[ MySQL Database ]  [ Proof Media ]              │
         │                                       │
         └─────────────────┬─────────────────────┘
                           │
                           ▼
          [ Blade Views + Tailwind CSS Engine ]
                           │
                           ▼
                   (Rendered HTML/CSS)

### 
