# ReCell 📱

**ReCell** is a web-based application designed to simplify the process of buying and selling second-hand smartphones. It simulates a small-scale business platform where users can list, browse, and interact over pre-owned mobile devices. Built as a DBMS mini-project, ReCell showcases full-stack integration with a strong focus on database design and functionality.

---

## 🚀 Features

- 🔐 **User Registration & Login** – Secure authentication for both buyers and sellers
- 📦 **Product Listings** – Sellers can upload mobile details including:
  - Brand, model, condition, price, age, and images
- 🔍 **Search & Filters** – Buyers can browse phones by brand, price range, or condition
- 💬 **In-app Messaging** – Users can chat to negotiate or inquire directly (simulated)
- 💰 **Payment Options** – Supports simulated Cash on Delivery (COD) and UPI (not fully implemented)
- 📊 **Transaction History** – Records user transactions (planned)
- 🗃️ **Well-Structured Database** – Normalized schema with ER diagrams, foreign keys, constraints

---

## 🧱 Tech Stack

| Layer       | Technology                     |
|-------------|--------------------------------|
| Frontend    | HTML, CSS, JavaScript          |
| Backend     | PHP                            |
| Database    | MySQL                          |
| Server      | XAMPP (Apache + MySQL + PHP)   |

---

## 🧠 Database Design

- Designed using **Entity Relationship Modeling (ER)**  
- Used concepts like:
  - Primary & Foreign Keys
  - Normalization (up to 3NF)
  - SQL Joins, Queries, and Triggers
- Entities:
  - Users
  - Products
  - Messages
  - Transactions
  - Categories

---

## 🧪 What Could Be Improved

- 💸 **Payment Simulation** is not fully functional — currently placeholder logic
- 🔔 **Real-time notifications & messaging** could be added
- 🛡️ **Input validation & security** could be tightened

---

## 📁 How to Run Locally

1. Install [XAMPP](https://www.apachefriends.org/)
2. Clone this repository
3. Move the project folder to `htdocs/` directory inside XAMPP
4. Import the `.sql` file into **phpMyAdmin**
5. Start Apache and MySQL via XAMPP control panel
6. Visit `localhost/ReCell` in your browser

---

## 🤝 Team & Contribution

This project was built collaboratively as part of our DBMS course.

**My Contributions:**
- My role here: database schema design, messaging module, UI work

---

## 📝 License

This project is for academic use only.
