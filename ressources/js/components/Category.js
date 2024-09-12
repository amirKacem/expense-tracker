import React, { useEffect, useState } from "react"

export default function Category() {

    const [categories,setCategories] = useState([]);

    useEffect(() => {
        fetch('http://localhost:8000/api/v1/categories')
        .then((res) => res.json())
        .then((data) => {
            console.log(data);
            setCategories(data);
        })
    },[])

    return (
    <table id="categoriesTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        {categories.map((Category) => (
            <tr key={Category.id}><td >{Category.title}</td></tr>
        ))}
    </tbody>
    </table>
    )
  }

