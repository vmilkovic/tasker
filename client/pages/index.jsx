import { getProviders, getSession, getCsrfToken } from "next-auth/client";
import Layout from "../components/layouts/Layout";
import Workspace from "../components/layouts/Workspace";
import Dashboard from "../components/layouts/Dashboard";
import Authenticate from "../components/layouts/Authenticate";

export default function Home({ session, csrfToken, providers }) {
  if (!session)
    return <Authenticate csrfToken={csrfToken} providers={providers} />;

  return (
    <Layout title="Tasker">
      <Workspace>Workspace</Workspace>
      <Dashboard>Dashboard</Dashboard>
    </Layout>
  );
}

export async function getServerSideProps(context) {
  return {
    props: {
      session: await getSession(context),
      csrfToken: await getCsrfToken(context),
      providers: await getProviders(),
    },
  };
}
