import Head from "next/head";
import Header from "../shared/Header";
import Sidebar from "../shared/Sidebar";
import Footer from "../shared/Footer";

function Dashboard({ title, children }) {
  return (
    <>
      <Head>
        <title>{title}</title>
      </Head>
      <Header />
      <main className="flex">
        <Sidebar />
        {children}
      </main>
      <Footer />
    </>
  );
}

export default Dashboard;
