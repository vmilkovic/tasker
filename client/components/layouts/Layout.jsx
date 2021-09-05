import Head from "next/head";
import styles from "../../styles/scss/components/layouts/Layout.module.scss";
import Header from "./Header";
import Footer from "./Footer";
import Sidebar from "./Sidebar";

export default function Layout({ children }) {
  return (
    <>
      <Header />
      <main>
        <Sidebar />
        {children}
      </main>
      <Footer />
    </>
  );
}
