import Head from "next/head";
import styles from "./layout.module.scss";

export default function Layout({ children }) {
  return (
    <>
      <Head>
        <title>Layouts Example</title>
      </Head>
      <main id={styles.main} className={styles.main}>
        {children}
      </main>
    </>
  );
}
