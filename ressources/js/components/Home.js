import React from "react"
export default function Home() {

    const handleClick = () => {
        alert('click');
    }
    return (
        <>     
         <img
        src="https://i.imgur.com/MK3eW3Am.jpg"
        alt="Katherine Johnson"
      />
      <button onClick={handleClick}>
      Click me
    </button>
    </>

    )
  }