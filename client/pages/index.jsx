import { signIn, signOut, useSession } from "next-auth/client";
import Welcome from "../components/common/presentational/Welcome";
import Authenticate from "../components/common/smart/Authenticate";
import Layout from "../components/layouts/Layout";

export default function Home() {
  return <Layout />;
}
