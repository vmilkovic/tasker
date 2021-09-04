import { signIn, signOut, useSession } from "next-auth/client";
import Welcome from "../components/common/Presentational/Welcome";
import Authenticate from "../components/common/Smart/Authenticate";
import Layout from "../components/layouts/Layout";

export default function Home() {
  const [session, loading] = useSession();
  return (
    <>
      {!session && (
        <>
          Not signed in <br />
          <button onClick={() => signIn()}>Sign inn</button>
        </>
      )}
      {session && (
        <>
          Signed in as {session.user.email} <br />
          <button onClick={() => signOut()}>Sign out</button>
        </>
      )}
    </>
  );
}
